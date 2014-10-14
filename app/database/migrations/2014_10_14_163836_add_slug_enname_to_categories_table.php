<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugEnnameToCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 文章分类 - 添加 slug(别名)、enname(英文名)、abbr(简写)
		Schema::table('article_categories', function($table){
		    $table->string('slug')->after('name');
		    $table->string('enname')->after('slug');
		    $table->string('abbr')->after('enname');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table){
		    $table->dropColumn('slug');
		    $table->string('enname');
		    $table->string('abbr');
		});
	}

}
