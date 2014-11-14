<?php

class Admin_ArticleResource extends BaseResource
{
    /**
     * 资源视图目录
     * @var string
     */
    protected $resourceView = 'admin.article';

    /**
     * 资源模型名称，初始化后转为模型实例
     * @var string|Illuminate\Database\Eloquent\Model
     */
    protected $model = 'Article';
    protected $modelCategory = 'Category';

    /**
     * 资源标识
     * @var string
     */
    protected $resource = 'articles';

    /**
     * 资源数据库表
     * @var string
     */
    protected $resourceTable = 'articles';

    /**
     * 资源名称（中文）
     * @var string
     */
    protected $resourceName = '文章';

    /**
     * 自定义验证消息
     * @var array
     */
    protected $validatorMessages = array(
        'title.required'   => '请填写文章标题。',
        'title.unique'     => '已有同名文章。',
        'slug.required'    => '请填写文章 sulg。',
        'slug.unique'      => '已有同名 sulg。',
        'content.required' => '请填写文章内容。',
        'category.exists'  => '请填选择正确的文章分类。',
    );

    /**
     * 资源列表页面
     * GET         /resource
     * @return Response
     */
    public function index()
    {
        // 获取排序条件
        $orderColumn = Input::get('sort_up', Input::get('sort_down', 'created_at'));
        $direction   = Input::get('sort_up') ? 'asc' : 'desc' ;
        // 获取搜索条件
        switch (Input::get('target')) {
            case 'title':
                $title = Input::get('like');
                break;
        }
        //  筛选
        $category = Input::get('category');
        $category_name = '';
        if($category != 0){
            $category_name = Category::find($category)->name;
        }
        // 构造查询语句
        $query = $this->model->orderBy($orderColumn, $direction)->whereNotNull('title');
        isset($title) AND $query->where('title', 'like', "%{$title}%");
        if(isset($category) && $category != 0){
            //subs
            $subs = Category::where('parent_id',$category)->lists('id');
            isset($subs) ? $subs[] = $category : $subs = array($category);
            $query->whereIn('category_id', $subs);
        }
        $datas = $query->paginate(15);
        //cates
        $categoryModel = App::make($this->modelCategory);
        $depth = $categoryModel->maxDepth;
        $categoryLists = $categoryModel->getCatesMuti($depth);
        //route
        $route = route($this->resource.'.index');

        return View::make($this->resourceView.'.index')->with(compact('datas', 'category_name', 'categoryLists', 'route'));
    }

    /**
     * 资源创建页面
     * GET         /resource/create
     * @return Response
     */
    public function create()
    {
        //cates
        $categoryModel = App::make($this->modelCategory);
        $depth = $categoryModel->maxDepth;
        $categoryLists = $categoryModel->getCatesMuti($depth);
        //create empty article
        $model = $this->model;
        $model->user_id = Auth::user()->id;
        $model->category_id = 1;
        $model->module_extend = 0;
        $model->save();
        //id
        $data_emptyID = $model->id;
        $data_preID = $this->model->orderBy('id', 'DESC')->first()->id+1;
        return View::make($this->resourceView.'.create')->with(compact('categoryLists', 'data_preID', 'data_emptyID'));
    }

    /**
     * 资源创建动作
     * POST        /resource
     * @return Response
     */
    public function store()
    {
        // 获取所有表单数据.
        $data   = Input::all();
        // 创建验证规则
        $rules  = array(
            'title'    => 'required',
            'category' => 'exists:article_categories,id',
        );
        // 自定义验证消息
        $messages = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功
            // 添加资源
            $id = e($data['id']);
            $model = $this->model->find($id);
            $model->user_id          = Auth::user()->id;
            $model->category_id      = $data['category'];
            $model->title            = e($data['title']);
            $model->slug             = e($data['slug']);
            $model->content          = e($data['content']);
            $model->module_extend    = e($data['module_extend']);
            $model->meta_title       = e($data['meta_title']);
            $model->meta_description = e($data['meta_description']);
            $model->meta_keywords    = e($data['meta_keywords']);
            if ($model->save()) {
                // 添加成功
                return Redirect::action('Admin_ArticleResource@edit', array($id))
                    ->with('success', '<strong>'.$this->resourceName.'添加成功：</strong>您可以编辑'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
            } else {
                // 添加失败
                return Redirect::back()
                    ->withInput()
                    ->with('error', '<strong>'.$this->resourceName.'添加失败。</strong>');
            }
        } else {
            // 验证失败
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /**
     * 资源编辑页面
     * GET         /resource/{id}/edit
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data = $this->model->find($id);
        //cates
        $categoryModel = App::make($this->modelCategory);
        $depth = $categoryModel->maxDepth;
        $categoryLists = $categoryModel->getCatesMuti($depth);

        return View::make($this->resourceView.'.edit')->with(compact('data', 'categoryLists'));
    }

    /**
     * 资源编辑动作
     * PUT/PATCH   /resource/{id}
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // 获取所有表单数据.
        $data = Input::all();
        // 创建验证规则
        $rules = array(
            'title'    => 'required',
            //'slug'     => 'required|'.$this->unique('slug', $id),
            //'content'  => 'required',
            'category' => 'exists:article_categories,id',
        );
        // 自定义验证消息
        $messages  = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功
            // 更新资源
            $model = $this->model->find($id);
            $model->category_id      = $data['category'];
            $model->title            = e($data['title']);
            $model->slug             = e($data['slug']);
            $model->content          = e($data['content']);
            $model->module_extend    = e($data['module_extend']);
            $model->meta_title       = e($data['meta_title']);
            $model->meta_description = e($data['meta_description']);
            $model->meta_keywords    = e($data['meta_keywords']);
            if ($model->save()) {
                // 更新成功
                return Redirect::back()
                    ->with('success', '<strong>'.$this->resourceName.'更新成功：</strong>您可以继续编辑'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
            } else {
                // 更新失败
                return Redirect::back()
                    ->withInput()
                    ->with('error', '<strong>'.$this->resourceName.'更新失败。</strong>');
            }
        } else {
            // 验证失败
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }
}
