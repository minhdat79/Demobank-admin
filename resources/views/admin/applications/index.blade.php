@extends('layouts.admin')
@section('title', 'Quản lý Hồ sơ Ứng tuyển')

@section('content')
<!-- Header Page -->
<div class="flex items-center justify-between mb-6">
  <div>
    <h1 class="text-2xl font-bold text-sky-900">Hồ sơ ứng tuyển</h1>
    <p class="text-slate-500 text-sm mt-1">Xét duyệt và quản lý CV từ ứng viên</p>
  </div>
</div>

<!-- Alert -->
@if(session('ok'))
  <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    <span class="font-medium">{{ session('ok') }}</span>
  </div>
@endif

<!-- Table Card -->
<div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-sky-100 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-slate-600">
      <thead class="bg-sky-50/80 text-sky-900 font-semibold border-b border-sky-100">
        <tr>
          <th class="px-6 py-4 whitespace-nowrap">Ứng viên</th>
          <th class="px-6 py-4 whitespace-nowrap">Vị trí ứng tuyển</th>
          <th class="px-6 py-4 whitespace-nowrap text-center">Trạng thái</th>
          <th class="px-6 py-4 whitespace-nowrap text-center">CV / Resume</th>
          <th class="px-6 py-4 whitespace-nowrap text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sky-50">
        @forelse($applications as $app)
          <tr class="hover:bg-sky-50/50 transition duration-150">
            
            <!-- Cột Ứng viên -->
            <td class="px-6 py-4">
              <div class="flex items-center gap-4">
                <!-- Avatar giả lập -->
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-sky-100 to-sky-200 border border-white shadow-sm flex items-center justify-center text-sky-700 font-bold text-sm shrink-0">
                  #{{ $app->id }}
                </div>
                <div>
                  <div class="font-bold text-slate-800 text-base">Hồ sơ #{{ $app->id }}</div>
                  <div class="text-xs text-slate-500 mt-0.5">
                    NS: {{ $app->dob }} • {{ $app->gender === 'male' ? 'Nam' : ($app->gender === 'female' ? 'Nữ' : 'Khác') }}
                  </div>
                  <div class="text-xs text-sky-600 mt-0.5 font-medium">Lương MM: {{ number_format($app->salary, 0, ',', '.') }}đ</div>
                </div>
              </div>
            </td>

            <!-- Cột Vị trí -->
            <td class="px-6 py-4">
              <div class="font-bold text-sky-700">{{ $app->job->title ?? 'Vị trí đã xóa' }}</div>
              <div class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Nộp lúc: {{ $app->created_at->format('H:i d/m/Y') }}
              </div>
            </td>

            <!-- Cột Trạng thái -->
            <td class="px-6 py-4 text-center">
              @if($app->status === 'pending')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Chờ duyệt
                </span>
              @elseif($app->status === 'approved')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đã duyệt
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Đã từ chối
                </span>
              @endif
            </td>

            <td class="px-6 py-4 text-center">
              <a href="http://127.0.0.1:8000/storage/{{ $app->cv_path }}" target="_blank" 
                 class="inline-flex items-center gap-1.5 text-sky-600 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 border border-sky-100 px-3 py-1.5 rounded-lg transition font-medium text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Mở file CV
              </a>
            </td>


            <td class="px-6 py-4">
              @if($app->status === 'pending')
                <div class="flex items-center justify-end gap-2">
                  <form action="{{ route('admin.applications.status', $app->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button class="bg-emerald-50 text-emerald-600 border border-emerald-100 hover:bg-emerald-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-sm">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                      Duyệt
                    </button>
                  </form>

                  <form action="{{ route('admin.applications.status', $app->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn từ chối hồ sơ này?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="rejected">
                    <button class="bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-500 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-sm">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                      Từ chối
                    </button>
                  </form>
                </div>
              @else
                <div class="text-right text-slate-400 text-xs italic font-medium flex items-center justify-end gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  Đã xử lý
                </div>
              @endif
            </td>

          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center">
              <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-sky-50 mb-3">
                <svg class="w-8 h-8 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
              </div>
              <p class="text-slate-500 font-medium">Chưa có hồ sơ ứng tuyển nào.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Phân trang -->
<div class="mt-6">
  {{ $applications->links() }}
</div>
@endsection