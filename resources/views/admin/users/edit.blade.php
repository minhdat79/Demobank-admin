@extends('layouts.admin')
@section('title','Sửa vai trò')

@section('content')
<h1 class="text-2xl font-bold text-sky-900 mb-6">Sửa vai trò: {{ $user->name }}</h1>

<form method="POST" action="{{ route('admin.users.update',$user) }}" class="max-w-xl">
  @csrf @method('PUT')
  <div class="bg-white/80 backdrop-blur rounded-2xl border border-sky-100 shadow-sm p-6 space-y-5">
    <div>
      <label class="block text-sm font-medium text-slate-700 mb-1">Vai trò</label>
      <select name="roles[]" class="w-full rounded-xl border-sky-200 focus:border-sky-500 focus:ring-sky-500" multiple size="6">
        @foreach($roles as $id => $name)
          <option value="{{ $name }}" @selected($user->hasRole($name))>{{ $name }}</option>
        @endforeach
      </select>
    </div>
    <div class="flex gap-2">
      <button class="px-5 py-2.5 rounded-xl bg-sky-500 text-white hover:bg-sky-600">Lưu</button>
      <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl bg-sky-100 text-sky-700 hover:bg-sky-200">Huỷ</a>
    </div>
  </div>
</form>
@endsection
