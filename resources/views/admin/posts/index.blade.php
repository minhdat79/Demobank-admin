@extends('layouts.admin')
@section('title','Bài viết')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold text-sky-900">📝 Bài viết</h1>
  <a href="{{ route('admin.posts.create') }}"
     class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600">
    + Đăng bài
  </a>
</div>

@if(session('status'))
  <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
    {{ session('status') }}
  </div>
@endif

@if($posts->count() === 0)
  <div class="text-slate-500">Chưa có bài viết nào.</div>
@else
  <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
    @foreach($posts as $p)

      @php
        $path = $p->image_path ?? '';

        if(!$path) {
            $cover = asset('images/placeholder-cover.jpg');
        } elseif(filter_var($path, FILTER_VALIDATE_URL)) {
            $cover = $path;
        } elseif(\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            $cover = asset('storage/'.$path);
        } else {
            $cover = asset('images/placeholder-cover.jpg');
        }
      @endphp

      <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 shadow-sm overflow-hidden">
        <img src="{{ $cover }}" class="w-full h-40 object-cover" alt="cover">

        <div class="p-4">
          <div class="text-xs text-slate-500 mb-1">
            Tác giả: <b>{{ $p->user->name ?? 'Ẩn danh' }}</b>
          </div>
          <h3 class="font-semibold text-slate-800 line-clamp-2">{{ $p->title }}</h3>
          <p class="mt-2 text-sm text-slate-600 line-clamp-3">
            {{ \Illuminate\Support\Str::limit(strip_tags($p->content), 160) }}
          </p>

          <div class="mt-4 flex items-center gap-2">
            <a href="{{ route('admin.posts.edit',$p) }}"
               class="px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 hover:bg-amber-400 text-sm">Sửa</a>

            <form method="POST" action="{{ route('admin.posts.destroy',$p) }}"
                  onsubmit="return confirm('Xoá bài viết này?')">
              @csrf @method('DELETE')
              <button class="px-3 py-1.5 rounded-lg bg-rose-500/90 text-white hover:bg-rose-600 text-sm">Xoá</button>
            </form>
          </div>
        </div>
      </div>

    @endforeach
  </div>

  <div class="mt-6">{{ $posts->links() }}</div>
@endif
@endsection
