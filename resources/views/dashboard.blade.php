@extends('layouts.admin')
@section('title','Bảng điều khiển')

@section('content')
{{-- Cards số liệu --}}
<div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5">
  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 p-5 shadow-sm">
    <div class="text-slate-500 text-sm">Người dùng</div>
    <div class="mt-1 text-3xl font-bold text-sky-700">{{ \App\Models\User::count() }}</div>
    <div class="mt-2 text-xs text-slate-400">Tổng số tài khoản</div>
  </div>

  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 p-5 shadow-sm">
    <div class="text-slate-500 text-sm">Bài viết</div>
    <div class="mt-1 text-3xl font-bold text-sky-700">{{ \App\Models\Post::count() }}</div>
    <div class="mt-2 text-xs text-slate-400">Nội dung đã đăng</div>
  </div>

  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 p-5 shadow-sm">
    <div class="text-slate-500 text-sm">Vai trò</div>
    <div class="mt-1 text-3xl font-bold text-sky-700">{{ \Spatie\Permission\Models\Role::count() }}</div>
    <div class="mt-2 text-xs text-slate-400">Phân quyền hệ thống</div>
  </div>

  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 p-5 shadow-sm">
    <div class="text-slate-500 text-sm">Hoạt động</div>
    <div class="mt-1 text-3xl font-bold text-sky-700">{{ now()->format('H:i') }}</div>
    <div class="mt-2 text-xs text-slate-400">Thời gian hệ thống</div>
  </div>
</div>

{{-- Activity / Quick links --}}
<div class="grid lg:grid-cols-3 gap-5 mt-6">
  <div class="lg:col-span-2 bg-white/80 backdrop-blur rounded-2xl border border-sky-100 p-5 shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <h3 class="font-semibold text-sky-900">Hoạt động gần đây</h3>
      <a href="{{ route('admin.posts.index') }}" class="text-sky-600 hover:text-sky-700 text-sm">Xem tất cả</a>
    </div>
    <ul class="divide-y divide-sky-50">
      <li class="py-3 flex items-start gap-3">
        <span class="mt-0.5 w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">📝</span>
        <div>
          <div class="font-medium">Bài viết mới</div>
          <div class="text-sm text-slate-500">Quản trị vừa đăng một bài viết.</div>
        </div>
      </li>
      <li class="py-3 flex items-start gap-3">
        <span class="mt-0.5 w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">👤</span>
        <div>
          <div class="font-medium">Tài khoản mới</div>
          <div class="text-sm text-slate-500">Một admin đã được tạo.</div>
        </div>
      </li>
    </ul>
  </div>

  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 p-5 shadow-sm">
    <h3 class="font-semibold text-sky-900 mb-3">Tạo nhanh</h3>
    <div class="grid gap-2">
      <a href="{{ route('admin.posts.create') }}" class="px-4 py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600 text-sm text-center">Bài viết mới</a>
      <a href="{{ route('admin.users.create') }}" class="px-4 py-2 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200 text-sm text-center">Tạo Admin</a>
    </div>
  </div>
</div>
@endsection
