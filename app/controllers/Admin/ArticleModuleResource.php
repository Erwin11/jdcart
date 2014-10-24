<?php

class Admin_ArticleModuleResource extends BaseResource
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
    protected $model = 'Module';

    /**
     * 资源标识
     * @var string
     */
    protected $resource = 'modules';

    /**
     * 资源数据库表
     * @var string
     */
    protected $resourceTable = 'article_modules';

    /**
     * 资源名称（中文）
     * @var string
     */
    protected $resourceName = '文章模块';

    /**
     * 自定义验证消息
     * @var array
     */
    protected $validatorMessages = array(
        'module_title.required'   => '请填写模块标题。'
    );

    /**
     * 模块内容 - 新增
     * @return json
     * 
     */
    public function postAddModule(){
        $data = Input::all();
        // 创建验证规则
        $rules  = array(
            'module_title'    => 'required'
        );
        // 自定义验证消息
        $messages  = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        //返回对象
        $responseObj = array();
        if ($validator->passes()) {
            // 验证成功
            $id = Input::get('module_id');
            // 添加模块内容
            $model = $this->model;
            $model->title    =     Input::get('module_title');
            $model->type     =     Input::get('module_type');
            $model->content  =     Input::get('module_content');
            //id
            $model->article_id = Input::get('article_id');
            $model->user_id    = Auth::user()->id;
            if ($model->save()) {
                //创建成功
                $data = array('id' => $id, 'title' => $model->title);
                $responseObj = array('status' => 'success', 'data' => $data);
            }else{
                // 创建失败
                $responseObj = array('status' => 'error');
            }
        }else{
              // 验证失败
              $validatorMsg = $validator->messages()->toArray();    //另一个方法：$validator->messages()->toJson();
              $responseObj = array('status' => 'verify', 'msg' => $validatorMsg);
        }
        return Response::json($responseObj);
    }

    /**
     * 模块内容 - 编辑
     * @return json
     * 
     */
    public function getEditModule(){
        $id = Input::get('id');
        $data = $this->model->find($id)->toArray();
        $responseObj = array('status' => 'success', 'data'=> $data);
        return Response::json($responseObj);
    }


    /**
     * 模块内容 - 更新
     * @return json
     * 
     */
    public function putEditModule(){
        $data = Input::all();
        // 创建验证规则
        $rules  = array(
            'module_title'    => 'required'
        );
        // 自定义验证消息
        $messages  = $this->validatorMessages;
        // 开始验证
        $validator = Validator::make($data, $rules, $messages);
        //返回对象
        $responseObj = array();
        if ($validator->passes()) {
            // 验证成功
            // 更新模块内容
            $id = Input::get('module_id');
            $model = $this->model->find($id);
            $model->title    =     Input::get('module_title');
            $model->type     =     Input::get('module_type');
            $model->content  =     Input::get('module_content');
            //id
            $model->article_id = Input::get('article_id');
            $model->user_id    = Auth::user()->id;
            if ($model->save()) {
                //创建成功
                $data = array('id' => $id, 'title' => $model->title);
                $responseObj = array('status' => 'success', 'data' => $data);
            }else{
                // 创建失败
                $responseObj = array('status' => 'error');
            }
        }else{
              // 验证失败
              $validatorMsg = $validator->messages()->toArray();    //另一个方法：$validator->messages()->toJson();
              $responseObj = array('status' => 'verify', 'msg' => $validatorMsg);
        }
        return Response::json($responseObj);
    }

    /**
     * 模块内容 - 删除
     * @return json
     * 
     */
    function getDeleteModule(){
        $id = Input::get('id');
        $data = $this->model->find($id);
        if (is_null($data))
            $responseObj = array('status' => 'error', 'msg' => '没有找到对应的'.$this->resourceName.'。');
        elseif ($data->delete())
            $responseObj = array('status' => 'success', 'msg' => '删除成功。');
        else
            $responseObj = array('status' => 'error', 'msg' => '删除失败。');
        return Response::json($responseObj);
    }
}
