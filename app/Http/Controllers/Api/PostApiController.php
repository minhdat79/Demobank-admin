<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostApiController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('search', ''));
        $posts = Post::when($q, function ($qb) use ($q) {
                        $qb->where('title', 'like', "%{$q}%")
                           ->orWhere('excerpt', 'like', "%{$q}%");
                    })
                    ->latest('published_at')
                    ->paginate(12);

        return PostResource::collection($posts);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->first()
              ?? Post::findOrFail(is_numeric($slug) ? (int)$slug : 0);

        return new PostResource($post);
    }
}
