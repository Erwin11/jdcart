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
| 公共变量
|--------------------------------------------------------------------------
*/
    /**
     * 最深层级
     * @var number
     */
    public $maxDepth = 999;

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
     * 多级菜单 - optionTd 前缀&nbsp;&nbsp;|—
     * @return string
     */
    public function getOptionTdAttribute()
    {   
        return '&nbsp;&nbsp;|—';
    }

    

/*
|--------------------------------------------------------------------------
| 工具方法 - utils
|--------------------------------------------------------------------------
*/ 
    /**
     * 多级菜单 - option 前缀 => &nbsp;&nbsp;
     * @return string
     */
    public function catePrefix($prefix = '&nbsp;&nbsp;'){
        $depth = $this->depth;
        $str = '';
        for ($i=0; $i <$depth ; $i++) { 
            $str .= $prefix;
        }
        return $str;
    }

    /**
     * 获取多级目录
     * @return array
     */
    public function getCatesMuti($depth)
    {   
        $rootCates = $this->where('parent_id', 0)->orderBy('sort_order')->get();
        $catesData = $this->getSubCates($rootCates, $depth);  
        return $catesData;
    }

    /**
     * 工具方法 - 递归获得子集目录
     * @param  array   $arr     父级数组
     * @param  number  $depth   层级深度
     * @return array
     */
    //utils 获得子级类目
    public function getSubCates($arr, $depth = null){
        if($depth && $depth<0){
            return array();
        }
        foreach ($arr as $item) {
            $subsArr = $this->where('parent_id', $item->id)->orderBy('sort_order')->get();
            if(count($subsArr)){
                if(($depth != null && $item->depth<$depth)){
                    $item->subs = $this->getSubCates($subsArr, $depth);
                }
            }
        }
        return $arr;
    }


}