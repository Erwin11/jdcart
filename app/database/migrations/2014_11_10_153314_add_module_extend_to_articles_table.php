<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddModuleExtendToArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 文章 - 添加 module_extend(模块内容展开)
		Schema::table('articles', function($table){
		    $table->boolean('module_extend')->after('content_format');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('articles', function($table){
				$table->dropColumn('module_extend');
		});
	}

}
