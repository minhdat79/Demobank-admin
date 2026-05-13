<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::latest()->paginate(10);
        // <-- TRẢ VIEW, KHÔNG PHẢI JSON
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image'   => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }
        $data['author_id'] = auth()->id();
        Post::create($data);

        return redirect()->route('admin.posts.index')->with('ok', 'Đã tạo bài viết');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'slug'    => 'required|string|max:255|unique:posts,slug,'.$post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image'   => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('posts', 'public');
        }
        $post->update($data);

        return redirect()->route('admin.posts.index')->with('ok', 'Đã cập nhật');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('ok', 'Đã xóa');
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }
}
