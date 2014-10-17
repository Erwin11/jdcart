<?php
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


}