@extends('layouts.admin')
@section('title','Người dùng')

@section('content')
<div class="flex items-center justify-between mb-6">
  <h1 class="text-2xl font-bold text-sky-900">👥 Người dùng</h1>
  <a href="{{ route('admin.users.create') }}"
     class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600">
     + Tạo Admin
  </a>
</div>

@if(session('status'))
  <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
    {{ session('status') }}
  </div>
@endif

<div class="overflow-x-auto bg-white/80 backdrop-blur rounded-2xl border border-sky-100 shadow-sm">
  <table class="min-w-full text-sm">
    <thead class="bg-sky-50 text-sky-800">
      <tr>
        <th class="text-left px-4 py-3">#</th>
        <th class="text-left px-4 py-3">Tên</th>
        <th class="text-left px-4 py-3">Email</th>
        <th class="text-left px-4 py-3">Vai trò</th>
        <th class="text-right px-4 py-3 w-40">Thao tác</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-sky-50">
      @foreach($users as $u)
      <tr class="hover:bg-sky-50/60">
        <td class="px-4 py-3">{{ $u->id }}</td>
        <td class="px-4 py-3 flex items-center gap-3">
          <img class="w-8 h-8 rounded-lg"
               src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=bae6fd&color=0c4a6e"
               alt="avt">
          <span>{{ $u->name }}</span>
        </td>
        <td class="px-4 py-3">{{ $u->email }}</td>
        <td class="px-4 py-3">
          @php $r = $u->roles->pluck('name'); @endphp
          @if($r->isEmpty())
            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs">Không có</span>
          @else
            @foreach($u->roles as $role)
              <span class="px-2 py-1 rounded-lg bg-sky-100 text-sky-700 text-xs">{{ $role->name }}</span>
            @endforeach
          @endif
        </td>
        <td class="px-4 py-3 text-right">
          <a href="{{ route('admin.users.edit',$u) }}"
             class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-400/90 text-slate-900 hover:bg-amber-400">
            Sửa
          </a>
          <form method="POST" action="{{ route('admin.users.destroy',$u) }}" class="inline"
                onsubmit="return confirm('Xoá người dùng này?')">
            @csrf @method('DELETE')
            <button class="inline-flex items-center px-3 py-1.5 rounded-lg bg-rose-500/90 text-white hover:bg-rose-600">
              Xoá
            </button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection
