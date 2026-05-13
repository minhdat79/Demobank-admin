<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Admin')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    /* thanh cuộn mềm */
    ::-webkit-scrollbar{width:10px;height:10px}
    ::-webkit-scrollbar-thumb{background:#cfe8ff;border-radius:9999px}
  </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-50 via-sky-100 to-white text-slate-800">
  <div class="min-h-screen flex">

    <aside class="hidden lg:flex lg:flex-col w-72 bg-white/70 backdrop-blur-xl border-r border-sky-100">
      <div class="h-16 px-6 flex items-center gap-3 border-b border-sky-100">
        <div class="w-9 h-9 rounded-xl bg-sky-500 flex items-center justify-center text-white font-bold">A</div>
        <div class="font-semibold text-sky-700">Trang quản trị</div>
      </div>

      <nav class="flex-1 py-4 px-3 space-y-1">
      
        @php
          function navcls($on){ return $on ? 'bg-sky-100/70 text-sky-900' : 'hover:bg-sky-50 text-slate-600'; }
        @endphp

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ navcls(request()->routeIs('admin.dashboard')) }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="1.6" d="m3 10.5 9-7 9 7V21H15v-6H9v6H3z"/>
          </svg>
          <span>Trang chủ</span>
        </a>

        <a href="{{ route('admin.posts.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ navcls(request()->routeIs('admin.posts.*')) }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="1.6" d="M6 4.5h12a1.5 1.5 0 0 1 1.5 1.5v12A1.5 1.5 0 0 1 18 19.5H6A1.5 1.5 0 0 1 4.5 18V6A1.5 1.5 0 0 1 6 4.5zM7.5 8.25h9M7.5 12h6M7.5 15.75h4.5"/>
          </svg>
          <span>Bài viết</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ navcls(request()->routeIs('admin.users.*')) }}">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="1.6" d="M16.5 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            <path stroke-width="1.6" d="M3.75 19.5a8.25 8.25 0 0 1 16.5 0"/>
          </svg>
          <span>Người dùng</span>
        </a>

        <a href="{{ route('admin.jobs.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ navcls(request()->routeIs('admin.jobs.*')) }}">
          {{-- icon briefcase --}}
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="1.6" d="M8 7V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1"/>
            <rect x="3" y="7" width="18" height="12" rx="2" stroke-width="1.6"/>
            <path stroke-width="1.6" d="M3 12h18"/>
          </svg>
          <span>Tuyển dụng</span>
        </a>

        {{-- MỤC HỒ SƠ MỚI ĐƯỢC THÊM VÀO ĐÂY --}}
        <a href="{{ route('admin.applications.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ navcls(request()->routeIs('admin.applications.*')) }}">
          {{-- icon document/file --}}
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <span>Hồ sơ ứng tuyển</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-xl transition hover:bg-sky-50 text-slate-600">
          {{-- icon settings --}}
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="1.6" d="M10 2.5h4l.5 2.5 2.2 1 .1 3 2 2-1.7 2.6.8 2.6-2.7 1.3L14 20.5h-4l-.5-2.2-2.7-1.3.8-2.6L5 12l.1-3 2.2-1L8.5 2.5z"/>
            <circle cx="12" cy="12" r="2.5" />
          </svg>
          <span>Cài đặt</span>
        </a>
      </nav>

      <div class="p-4 border-t border-sky-100">
        <div class="flex items-center gap-3 mb-3">
          {{-- avatar từ ui-avatars --}}
          <img class="w-10 h-10 rounded-xl object-cover"
               src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=38bdf8&color=fff"
               alt="avatar">
          <div class="text-sm">
            <div class="font-semibold text-sky-800">{{ auth()->user()->name ?? '—' }}</div>
            <div class="text-slate-500">{{ auth()->user()->email ?? '' }}</div>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="w-full py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-600 transition">Đăng xuất</button>
        </form>
      </div>
    </aside>

    {{-- CONTENT --}}
    <div class="flex-1 min-h-screen">
      {{-- TOP BAR --}}
      <header class="h-16 sticky top-0 z-30 bg-white/60 backdrop-blur-xl border-b border-sky-100">
        <div class="h-full px-4 lg:px-8 flex items-center justify-between">
          <div class="font-semibold text-sky-800">@yield('title','')</div>
          <div class="flex items-center gap-3">
            {{-- quick actions --}}
            <a href="{{ route('admin.posts.create') }}"
               class="hidden md:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-sky-100 text-sky-800 hover:bg-sky-200">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="1.6" d="M12 4.5v15m7.5-7.5h-15"/>
              </svg>
              Bài mới
            </a>
            <div class="text-sm text-slate-500">Xin chào, <b>{{ auth()->user()->name ?? 'User' }}</b></div>
          </div>
        </div>
      </header>

      <main class="p-4 lg:p-8">
        @yield('content')
      </main>
    </div>
  </div>
</body>
</html>