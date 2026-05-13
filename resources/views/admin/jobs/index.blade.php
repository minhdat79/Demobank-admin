@extends('layouts.admin')
@section('title','Tuyển dụng')

@section('content')
<style>
/* ===== Admin list – chỉ CSS, giữ markup cũ ===== */
.admin-jobs .card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden}
.admin-jobs table{width:100%;font-size:14px;border-collapse:separate;border-spacing:0}
.admin-jobs thead th{background:#f8fafc;color:#334155;padding:12px 14px;text-align:left;font-weight:700}
.admin-jobs tbody td{border-top:1px solid #e5e7eb;padding:12px 14px;vertical-align:top}
.admin-jobs .status{display:inline-flex;align-items:center;padding:4px 8px;border-radius:999px;font-size:12px}
.admin-jobs .status.open{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
.admin-jobs .status.closed{background:#f1f5f9;color:#475569;border:1px solid #e2e8f0}
.admin-jobs .row-actions a{margin-left:12px}
</style>

<div class="admin-jobs">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Tuyển dụng</h1>
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">+ Đăng tin</a>
  </div>

  @if(session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div> @endif

  <div class="card">
    <table>
      <thead>
        <tr>
          <th>Vị trí</th>
          <th>Phòng ban</th>
          <th>Địa điểm</th>
          <th>Hình thức</th>
          <th>Trạng thái</th>
          <th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $it)
          <tr>
            <td>
              <div class="font-medium text-slate-900">{{ $it->title }}</div>
              <div class="text-xs text-slate-500 mt-1">Đăng: {{ $it->publish_date?->format('d/m/Y') }}</div>
            </td>
            <td class="text-center">{{ $it->department }}</td>
            <td class="text-center">{{ $it->location }}</td>
            <td class="text-center">{{ $it->employment_type }}</td>
            <td class="text-center">
              <span class="status {{ $it->status==='open' ? 'open' : 'closed' }}">
                {{ $it->status==='open' ? 'Mở' : 'Đóng' }}
              </span>
            </td>
            <td class="row-actions" style="text-align:right;white-space:nowrap">
              <a class="text-sky-600 hover:underline" href="{{ route('admin.jobs.edit',$it) }}">Sửa</a>
              <form action="{{ route('admin.jobs.destroy',$it) }}" method="POST" class="inline" onsubmit="return confirm('Xoá tin này?')" style="display:inline">
                @csrf @method('DELETE')
                <button class="text-rose-600 ml-3" style="background:none;border:0;padding:0;cursor:pointer">Xoá</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $items->links() }}</div>
</div>
@endsection
