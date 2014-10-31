<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleEnToArticleModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 文章的模块内容 - 添加 title_en(英文标题)
		Schema::table('article_modules', function($table){
		    $table->string('title_en')->after('title');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('article_modules', function($table){
		    $table->dropColumn('title_en');
		});
	}

}
