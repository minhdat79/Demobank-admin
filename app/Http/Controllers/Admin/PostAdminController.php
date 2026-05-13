<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PostAdminController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => ['required','string','max:255'],
            'excerpt' => ['nullable','string'],
            'content' => ['required','string'],
            'image'   => ['nullable','image'],
            'slug'    => ['nullable','string','max:255', Rule::unique('posts','slug')],
            'status'        => ['nullable','string'],
            'published_at'  => ['nullable','date'],
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $base = $slug; $i = 2;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('posts', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['author_name'] = optional(auth()->user())->name;
        $validated['status'] = $validated['status'] ?? 'published';
        $validated['published_at'] = ($validated['status'] === 'published') ? now() : null;
        $validated['slug'] = $slug;

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('ok','Đã lưu bài viết');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'   => ['required','string','max:255'],
            'excerpt' => ['nullable','string'],
            'content' => ['required','string'],
            'image'   => ['nullable','image'],
            'slug'    => ['nullable','string','max:255', Rule::unique('posts','slug')->ignore($post->id)],
            'status'        => ['nullable','string'],
            'published_at'  => ['nullable','date'],
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $base = $slug; $i = 2;
        while (Post::where('slug',$slug)->where('id','!=',$post->id)->exists()) {
            $slug = $base.'-'.$i++;
        }

        if ($request->hasFile('image')) {
            if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
                Storage::disk('public')->delete($post->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('posts','public');
        }

        $validated['author_name'] = optional(auth()->user())->name;
        $validated['slug'] = $slug;

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('ok','Đã cập nhật');
    }

    public function destroy(Post $post)
    {
        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('ok','Đã xóa');
    }
}
