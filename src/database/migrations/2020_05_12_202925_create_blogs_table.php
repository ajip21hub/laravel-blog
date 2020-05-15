<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Prefix.
     */
    private $prefix;

    /**
     * Run the migrations.
     */
    public function up()
    {
        $this->prefix = config('blog.table.prefix', 'blog_');

        Schema::create($this->prefix.'posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('img')->nullable();
            $table->string('title');
            $table->text('content')->nullable();
            $table->unsignedInteger('author_id')->nullable();
            $table->boolean('commentable')->default(1);
            $table->boolean('visibled')->default(1);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create($this->prefix.'post_meta', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->string('key');
            $table->text('content')->nullable();

            $table->primary(['post_id', 'key']);
            $table->foreign('post_id')->references('id')->on($this->prefix.'posts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create($this->prefix.'post_likes', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->unsignedInteger('liker_id');

            $table->primary(['post_id', 'liker_id']);
            $table->foreign('post_id')->references('id')->on($this->prefix.'posts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create($this->prefix.'post_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('post_id');
            $table->text('content');
            $table->unsignedInteger('commentator_id')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on($this->prefix.'posts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create($this->prefix.'categories', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('desription')->nullable();
            $table->timestamps();
        });

        Schema::create($this->prefix.'post_categories', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->unsignedSmallInteger('category_id');

            $table->primary(['post_id', 'category_id']);
            
            $table->foreign('post_id')->references('id')->on($this->prefix.'posts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on($this->prefix.'categories')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create($this->prefix.'post_tags', function (Blueprint $table) {
            $table->unsignedInteger('post_id');
            $table->string('name');

            $table->primary(['post_id', 'name']);

            $table->foreign('post_id')->references('id')->on($this->prefix.'posts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->prefix = config('blog.table.prefix', 'blog_');

        Schema::dropIfExists($this->prefix.'post_tags');
        Schema::dropIfExists($this->prefix.'post_categories');
        Schema::dropIfExists($this->prefix.'categories');
        Schema::dropIfExists($this->prefix.'post_comments');
        Schema::dropIfExists($this->prefix.'post_likes');
        Schema::dropIfExists($this->prefix.'post_meta');
        Schema::dropIfExists($this->prefix.'posts');
    }
}
