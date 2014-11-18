<?php

class BlogController extends BaseController
{
    /**
     * 博客首页
     * @return Respanse
     */
    public function getIndex()
    {
        $categories = Category::where('parent_id', 0)->orderBy('sort_order')->get();
        return View::make('blog.home')->with(compact('categories'));
    }

    /**
     * 博客列表页
     * @return Respanse
     */
    public function getList()
    {
        $articles   = Article::orderBy('created_at', 'desc')->paginate(5);
        $categories = Category::where('parent_id', 0)->orderBy('sort_order')->get();
        return View::make('blog.index')->with(compact('articles', 'categories'));
    }

    /**
     * 分类文章列表
     * @return Respanse
     */
    public function getCategoryArticles($category_id)
    {
        $articles   = Article::where('category_id', $category_id)->orderBy('created_at', 'asc')->paginate(5);
        $categories = Category::where('parent_id', 0)->orderBy('sort_order')->get();
        //firstArticle
        $cateItem = Category::find($category_id);
        $cateSubs = Category::where('parent_id', $cateItem->id)->orderBy('sort_order')->first();
        if($cateSubs){
            $firstArticle = Article::where('category_id', $cateSubs->id)->orderBy('created_at','asc')->first();
            return Redirect::to($firstArticle->slug);
        }
        return View::make('blog.categoryArticles')->with(compact('articles', 'categories', 'category_id'));
    }


    /**
     * 分类文章列表-别名 - 一级类目
     * @return Respanse
     */
    public function categoryArticlesSlug($category_slug)
    {
        $category_id = Category::where('slug', $category_slug)->pluck('id');
        $articles   = Article::where('category_id', $category_id)->orderBy('created_at', 'asc')->paginate(5);
        $categories = Category::where('parent_id', 0)->orderBy('sort_order')->get();
        //firstArticle
        $cateItem = Category::find($category_id);
        $cateSubs = Category::where('parent_id', $cateItem->id)->orderBy('sort_order')->first();
        if($cateSubs){
            $firstArticle = Article::where('category_id', $cateSubs->id)->orderBy('created_at','asc')->first();
            return Redirect::route('blog.show', array('id' => $firstArticle->id));
        }
        return View::make('blog.categoryArticles')->with(compact('articles', 'categories', 'category_id'));
    }

    /**
     * 分类文章列表-别名 - 子级级类目
     * @return Respanse
     */
    public function getSubCategoryArticles($id){
        $category_id = $id;
        $categories = Category::where('parent_id', 0)->orderBy('sort_order')->get();
        //firstArticle
        $cateItem = Category::find($id);
        $articles = Article::where('category_id', $cateItem->id)->orderBy('created_at','asc');
        if($articles->first()){
            $firstArticle = $articles->first();
            return Redirect::route('blog.show', array('id' => $firstArticle->id));    
        }
        $cateSubs = Category::where('parent_id', $cateItem->parent_id)->orderBy('sort_order')->get();
        return View::make('blog.categoryArticles')->with(compact('articles', 'categories', 'category_id', 'cateSubs'));  
    }

    /**
     * 博客文章展示页面
     * @param  string $slug 文章缩略名
     * @param  stirng $id   文章id
     * @return response
     */
    public function getBlogShow($id)
    {
        
        // $article    = Article::where('slug', $slug)->first();
        $article    = Article::find($id);
        is_null($article) AND App::abort(404);
        $categories = Category::where('parent_id', 0)->orderBy('sort_order')->get();
        $category_id = $article->category_id;
        //subs
        $cateItem = Category::find($category_id);
        $cate_parentid = $cateItem->parent_id;
        $cateSubs = Category::where('parent_id', $cateItem->parent_id)->orderBy('sort_order')->get();
        $articles   = Article::where('category_id', $category_id)->orderBy('created_at', 'asc')->whereNotNull('title')->get();
        return View::make('blog.show')->with(compact('article', 'articles', 'categories', 'category_id','cateItem', 'cateSubs', 'cate_parentid'));
    }

    /**
     * 提交评论
     * @param  string $slug 文章缩略名
     * @return response
     */
    public function postBlogComment($slug)
    {
        // 获取评论内容
        $content = e(Input::get('content'));
        // 字数检查
        if (mb_strlen($content)<3)
            return Redirect::back()->withInput()->withErrors($this->messages->add('content', '评论不得少于3个字符。'));
        // 查找对应文章
        $article = Article::where('slug', $slug)->first();
        // 创建文章评论
        $comment = new Comment;
        $comment->content    = $content;
        $comment->article_id = $article->id;
        $comment->user_id    = Auth::user()->id;
        if ($comment->save()) {
            // 创建成功
            // 更新评论数
            $article->comments_count = $article->comments->count();
            $article->save();
            // 返回成功信息
            return Redirect::back()->with('success', '评论成功。');
        } else {
            // 创建失败
            return Redirect::back()->withInput()->with('error', '评论失败。');
        }
    }

}
