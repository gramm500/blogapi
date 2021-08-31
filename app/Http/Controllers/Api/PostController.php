<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use http\Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PostController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $posts = Post::paginate();

        return PostResource::collection($posts);
    }

    public function store(PostRequest $request): PostResource
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $validated['user_id'] = $user->id;
        $validated['slug'] = Str::slug($validated['title']);


        $post = Post::create($validated);

        $post->tags()->sync($validated['tags']);

        $post->load(['tags']);

        return new PostResource($post);
    }

    /**
     * @param PostRequest $request
     * @param Post $post
     * @return PostResource
     */
    public function update(PostRequest $request, Post $post): PostResource
    {
        $user = $request->user();
        $validated = $request->validated();
        if ($post->user_id !== $user->id) {
            throw new AccessDeniedHttpException();
        }

        $post->update($validated);
        $post->tags()->sync($validated['tags']);

        $post->load(['tags']);

        return new PostResource($post);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return void
     * @throws Exception|\Exception
     */
    public function destroy(Request $request, Post $post): void
    {
        /** @var User $user */
        $user = $request->user();

        if ($post->user_id !== $user->id) {
            throw new AccessDeniedHttpException();
        }

        $post->comments()->delete();
        $post->delete();
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post);
    }

    public function search(SearchRequest $request): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->where('title', 'LIKE', "%{$request['q']}%")
            ->get();
        $posts->load(['tags']);

        return PostResource::collection($posts);
    }
}
