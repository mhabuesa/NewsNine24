<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('category_id');
            $table->integer('subcategory_id')->nullable();
            $table->longText('short_description');
            $table->longText('description');
            $table->text('video_url')->nullable();
            $table->string('image')->nullable();
            $table->string('slug');
            $table->string('status')->default('published');
            $table->dateTime('scheduled_at')->nullable();
            $table->string('fb_post_id')->nullable();
            $table->string('is_featured')->nullable();
            $table->string('is_hot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
