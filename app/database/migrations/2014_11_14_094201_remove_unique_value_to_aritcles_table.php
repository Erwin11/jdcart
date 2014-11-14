<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueValueToAritclesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 文章 - 去除唯一性索引等
		Schema::table('articles', function($table){
		    $table->dropUnique('articles_title_unique');
		    $table->dropUnique('articles_slug_unique');
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
				$table->unique('title');
				$table->unique('slug');
		});
	}

}
