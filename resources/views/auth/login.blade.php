@vite(['resources/css/app.css','resources/js/app.js'])

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Đăng nhập</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-500 via-sky-600 to-blue-700 flex items-center justify-center">
  <div class="w-full max-w-md bg-white/90 backdrop-blur shadow-xl rounded-2xl p-8">
    <h1 class="text-center text-2xl font-bold text-slate-800 mb-6">SGB-Đăng nhập</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm text-slate-600 mb-1">Email</label>
        <input type="email" name="email" required autofocus
               class="w-full rounded-xl border-slate-300 focus:border-sky-500 focus:ring-sky-500">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">Mật khẩu</label>
        <input type="password" name="password" required
               class="w-full rounded-xl border-slate-300 focus:border-sky-500 focus:ring-sky-500">
      </div>
      <button class="w-full py-2.5 rounded-xl bg-sky-600 text-white font-semibold hover:bg-sky-700 transition">
        Đăng nhập
      </button>
    </form>
  </div>
</body>
</html>
