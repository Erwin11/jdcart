<?php
/**
 * 文章分类
 */
class Category extends BaseModel
{
    /**
     * 数据库表名称（不包含前缀）
     * @var string
     */
    protected $table = 'article_categories';

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
     * 分类下的文章
     * 一对多
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function articles()
    {
        return $this->hasMany('Article', 'category_id');
    }

    /**
     * 多级分类
     * 一对多
     * @return object Illuminate\Database\Eloquent\Collection
     */
    public function multiCates()
    {
        return $this->hasMany('Category', 'parent_id');
    }


/*
|--------------------------------------------------------------------------
| 访问器
|--------------------------------------------------------------------------
*/
    /**
     * 多级菜单 - option 前缀&nbsp;
     * @return string
     */
    public function getOptionPrefixAttribute()
    {   
        $depth = $this->depth;
        $str = '';
        for ($i=0; $i <$depth ; $i++) { 
            $str .= '&nbsp&nbsp';
        }
        return $str;
    }

    /**
     * 多级菜单 - option 前缀&nbsp;
     * @return string
     */
    public function getOptionTdAttribute()
    {   
        $depth = $this->depth;
        $str = '';
        for ($i=0; $i <$depth ; $i++) { 
            $str .= '&nbsp;&nbsp;|—';
        }
        return $str;
    }

}