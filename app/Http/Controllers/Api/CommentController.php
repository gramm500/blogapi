<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Routing\Controller;

class CommentController extends Controller
{
    public function index(Post $post): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $comments = Comment::where('post_id', $post->id)->get();

        return CommentResource::collection($comments);
    }

    public function store(CommentRequest $request, Post $post): CommentResource
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $validated['post_id'] = $post->id;

        $comment = Comment::updateOrCreate($validated);
        return new CommentResource($comment);
    }
}
