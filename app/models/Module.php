<?php

use \Michelf\MarkdownExtra;

/**
 * 文章模块内容
 */
class Module extends BaseModel
{
    /**
     * 数据库表名称（不包含前缀）
     * @var string
     */
    protected $table = 'article_modules';

    /**
     * 软删除
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * json转换隐藏属性
     * 
     * @var object
     */
    protected $hidden = array('article_id', 'user_id', 'created_at', 'updated_at');

/*
|--------------------------------------------------------------------------
| 模型对象关系
|--------------------------------------------------------------------------
*/
    /**
     * 归属文章
     * 一对多逆向
     * @return object Article
     */
    public function article()
    {
        return $this->belongsTo('Article', 'article_id');
    }

    /**
     * 模块的作者
     * 一对一逆向
     * @return object User
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }


/*
|--------------------------------------------------------------------------
| 访问器
|--------------------------------------------------------------------------
*/
    /**
     * 模块内容 - 上传图片
     * @return json 图片数组
     */
    public function getImageArrayAttribute()
    {
        if ($this->image)
            return json_decode($this->image);
    }

    /**
     * 模块内容 - 上传文件（json 格式）
     * @return json
     */
    public function getDownloadArrayAttribute()
    {
        if ($this->download)
            return json_decode($this->download);
    }

    /**
     * 模块内容 - 上传文件
     * @return string 文件的URI
     */
    public function getDownloadUrlAttribute()
    {
        if ($this->download_array)
            return asset($this->download_array->url);
    }

    /**
     * 模块内容（HTML 格式）
     * @return string
     */
    public function getContentHtmlAttribute()
    {
        return MarkdownExtra::defaultTransform($this->content);
    }

    /**
     * 模块内容（Markdown 格式）
     * @return string
     */
    public function getContentMarkdownAttribute()
    {
        return $this->content;
    }

}