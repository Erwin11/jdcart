<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToArticleCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 文章分类 - 添加 parent_id(父类id)
		Schema::table('article_categories', function($table){
		    $table->integer('parent_id')->after('sort_order');
		    $table->integer('depth')->after('parent_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('article_categories', function($table){
		    $table->dropColumn('parent_id');
		    $table->dropColumn('depth');
		});
	}

}
