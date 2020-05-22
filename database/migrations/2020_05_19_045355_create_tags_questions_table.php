<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tags_questions',function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('tags_id');
            $table->unsignedInteger('questions_id');
            $table->primary(['tags_id','questions_id']);
            $table->timestamps();
            // 外部キー制約
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('questions_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            //
        });
    }
}
