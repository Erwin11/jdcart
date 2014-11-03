<?php

class Admin_CategoryResource extends BaseResource
{
    /**
     * 资源视图目录
     * @var string
     */
    protected $resourceView = 'admin.category';

    /**
     * 资源模型名称，初始化后转为模型实例
     * @var string|Illuminate\Database\Eloquent\Model
     */
    protected $model = 'Category';

    /**
     * 资源标识
     * @var string
     */
    protected $resource = 'categories';

    /**
     * 资源数据库表
     * @var string
     */
    protected $resourceTable = 'article_categories';

    /**
     * 资源名称（中文）
     * @var string
     */
    protected $resourceName = '文章分类';

    /**
     * 自定义验证消息
     * @var array
     */
    protected $validatorMessages = array(
        'name.required' => '请填写分类名称。',
        'name.unique'   => '已有同名分类。',
        'sort_order.required' => '请填写分类排序。',
        'sort_order.integer'  => '请填写一个整数。',
    );

    /**
     * 资源列表页面
     * GET         /resource
     * @return Response
     */
    public function index()
    {
        $datas = $this->model->orderBy('sort_order')->paginate(15);
        return View::make($this->resourceView.'.index')->with(compact('datas'));
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
        $unique = $this->unique();
        $rules  = array(
            'name'       => 'required|'.$unique,
            'sort_order' => 'required|integer',
        );
        // 自定义验证消息
        $messages = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功
            // 添加资源
            $model = $this->model;
            $model->name       = e($data['name']);
            $model->sort_order = e($data['sort_order']);
            if ($model->save()) {
                // 添加成功
                return Redirect::back()
                    ->with('success', '<strong>'.$this->resourceName.'添加成功：</strong>您可以继续添加新'.$this->resourceName.'，或返回'.$this->resourceName.'列表。');
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
        //parents
        $parent_id  = $this->model->parent_id;
        $parents = $this->model->orderBy('sort_order')->lists('name', 'id');
        //cates
        $depth = $data->depth - 1;
        $cates = $this->model->where('parent_id', 0)->orderBy('sort_order')->get();
        $catesData = $this->getCates($cates, $depth);

        return View::make($this->resourceView.'.edit')->with(compact('data', 'parents', 'catesData'));
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
            'name'       => 'required|'.$this->unique('name', $id),
            'sort_order' => 'required|integer',
        );
        // 自定义验证消息
        $messages  = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->passes()) {
            // 验证成功
            // 更新资源
            $model = $this->model->find($id);
            $model->name       = e($data['name']);
            $model->slug       = e($data['slug']);
            $model->enname     = e($data['enname']);
            $model->abbr       = e($data['abbr']);
            $model->sort_order = e($data['sort_order']);
            $model->parent_id  = e($data['parent_id']);
            //depth
            $depth             = $this->model->where('id', $model->parent_id)->first()->depth;
            $model->depth      = $depth + 1;
            //
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

    public function testCate($id){
        // $data = $this->model->find($id);
        $parent_id = 0;
        $cates = $this->model->where('parent_id', $parent_id)->orderBy('sort_order')->get();
        
        $data = $this->getCates($cates, 2);

        return $data;
        // $id = 4;
        /*$ancestors = Category::where('id', '=', $id)->get();

        while ($ancestors->last()->parent_id !== 0)
        {
          $parent = Category::where('id', '=', $ancestors->last()->parent_id)->get();
          $subs = Category::where('parent_id', $parent->last()->parent_id)->get();
          $parent->last()->subs = json_decode($subs);
          $ancestors = $ancestors->merge($parent);
        }

        return $ancestors;*/
    }



    /*========================================= utils ===========================================*/
    /**
     * 工具方法 - 递归获得子集目录
     * @param  array   $arr     父级数组
     * @param  number  $depth   层级深度
     * @return array
     */
    //utils 获得子级类目
    private function getCates($arr, $depth = null){
        foreach ($arr as $item) {
            $subsArr = $this->model->where('parent_id', $item->id)->get();
            
            if(count($subsArr)){
                if($depth != null && $item->depth<$depth){
                    $item->subs = $this->getCates($subsArr, $depth);
                }
            }
        }
        return $arr;
    }
}
