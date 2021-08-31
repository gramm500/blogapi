<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\TagRequest;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class TagController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tags = Tag::all();
        return TagResource::collection($tags);
    }

    public function store(TagRequest $request): TagResource
    {
        $validated = $request->validated();
        $tag = Tag::create($validated);

        return new TagResource($tag);
    }

    public function update(TagRequest $request, Tag $tag): TagResource
    {
        $validated = $request->validated();
        $tag->update($validated);

        return new TagResource($tag);
    }

    /**
     * @param Tag $tag
     * @throws \Exception
     */

    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }

    public function destroy(Tag $tag): void
    {
        $tag->delete();
    }

    public function search(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $searchTags = explode(',', $validated['q']);

        $te = Post::whereHas(
            'tags',
            function ($query) use ($searchTags) {
                $query->where('name', 'LIKE', "%{$searchTags[0]}%");
                foreach ($searchTags as $tag) {
                    $query->orWhere('name', 'LIKE', "%{$tag}%");
                }
            }
        )->get();
        return response()->json(['posts' => $te], 200);
    }
}
