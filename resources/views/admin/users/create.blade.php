@extends('layouts.admin')
@section('title','Tạo Admin mới')

@section('content')
<h1 class="text-2xl font-bold text-sky-900 mb-6">Tạo Admin mới</h1>

@if ($errors->any())
  <div class="mb-4 p-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-sm">
    <ul class="list-disc ml-5">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('admin.users.store') }}" class="max-w-xl">
  @csrf
  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 shadow-sm p-6 space-y-5">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Họ tên</label>
      <input name="name" value="{{ old('name') }}"
             class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
      <input type="email" name="email" value="{{ old('email') }}"
             class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" required>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Mật khẩu</label>
      <input type="password" name="password"
             class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" required>
      <p class="text-xs text-slate-500 mt-1">Tối thiểu 8 ký tự.</p>
    </div>
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Xác nhận mật khẩu</label>
      <input type="password" name="password_confirmation"
             class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" required>
    </div>
    <div class="flex gap-2">
      <button class="px-5 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-600">Tạo Admin</button>
      <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200">Huỷ</a>
    </div>
  </div>
</form>
@endsection
