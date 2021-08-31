<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'comments',
            function (Blueprint $table) {
                $table->id();

                $table->text('body')->nullable();

                $table->unsignedInteger('post_id');
                $table->unsignedInteger('user_id');

                $table->timestamps();

                $table->foreign('post_id')->references('id')->on('posts');
                $table->foreign('user_id')->references('id')->on('users');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
}
