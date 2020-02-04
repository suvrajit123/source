<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('category_id')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('license_type')->nullable();
            $table->string('resource_attachment')->nullable();
            $table->string('cover_attachment')->nullable();
            $table->string('preview_attachment')->nullable();
            $table->string('embed_link')->nullable();
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->string('resource_status')->default('drafted'); // drafted,inreview,published,rejected
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('isFeatured')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
