<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\LikeResource;
use App\Models\Post;
use Illuminate\Routing\Controller;
use JetBrains\PhpStorm\Pure;

class LikeController extends Controller
{
    #[Pure] public function index(Post $post): LikeResource
    {
        return new LikeResource($post);
    }

    public function addLike(Post $post): LikeResource
    {
        $post->query()->increment('likes');

        return new LikeResource($post);
    }
}
