<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(
            'likes',
            function (Blueprint $table) {
                $table->unsignedInteger('post_id');
                $table->unsignedInteger('user_id');

                $table->foreign('post_id')->references('id')->on('posts');
                $table->foreign('user_id')->references('id')->on('users');

                $table->timestamps();
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
        Schema::dropIfExists('likes');
    }
}
