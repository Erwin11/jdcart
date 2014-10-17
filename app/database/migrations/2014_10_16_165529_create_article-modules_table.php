<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// 文章的模块内容
	  Schema::create('article_modules', function ($table) {

      $table->increments('id');
      $table->integer('user_id'   )->unsigned()->comment('作者ID');
      $table->integer('article_id')->unsigned()->comment('归属文章ID');
      $table->string('type'				)->nullable()->comment('类型');
      $table->string('title'				)->nullable()->comment('标题');
      $table->text('content'			)->nullable()->comment('内容');
      $table->string('image'				)->nullable()->comment('图片');
      $table->string('download'			)->nullable()->comment('下载内容');
      $table->integer('sort_order')->unsigned()->default(0)->comment('排序');

      $table->timestamps();
      $table->softDeletes();

      $table->comment = '文章的模块内容';
      $table->engine  = 'MyISAM';
      $table->index('user_id');
      $table->index('article_id');
	  });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('article_modules');
	}

}
