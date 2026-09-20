<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#f43f5e">
<title>@yield('title', 'Ovu')</title>
<link rel="manifest" href="/manifest.webmanifest">
<link rel="icon" href="/icons/icon.svg" type="image/svg+xml">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-rose-50 text-stone-800 antialiased">
<header class="sticky top-0 z-10 border-b border-rose-100 bg-white/90 backdrop-blur">
  <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-3">
    <a href="{{ auth()->check() ? route('dashboard') : route('landing') }}" class="text-xl font-bold text-rose-600">Ovu</a>
    <nav class="flex items-center gap-3 text-sm">
      @auth
        <a href="{{ route('dashboard') }}" class="hover:text-rose-600">Dasbor</a>
        <a href="{{ route('calendar') }}" class="hover:text-rose-600">Kalender</a>
        <a href="{{ route('logs.index') }}" class="hover:text-rose-600">Catatan</a>
        <a href="{{ route('analytics') }}" class="hover:text-rose-600">Analitik</a>
        <a href="{{ route('settings') }}" class="hover:text-rose-600">Pengaturan</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
          <button class="text-stone-500 hover:text-rose-600">Keluar</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="hover:text-rose-600">Masuk</a>
        <a href="{{ route('register') }}" class="rounded-full bg-rose-600 px-4 py-1.5 text-white">Daftar</a>
      @endauth
    </nav>
  </div>
</header>
<main class="mx-auto w-full max-w-3xl px-4 pb-16 pt-6">
  @if (session('ok'))
    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-100 px-4 py-3 text-sm text-rose-800">{{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
      <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif
  @yield('content')
</main>
<footer class="border-t border-rose-100 bg-white">
  <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-4 text-xs text-stone-500">
    <span>Ovu. Data milikmu, bukan untuk dijual.</span>
    <span class="flex gap-3"><a href="{{ route('privacy') }}" class="hover:text-rose-600">Privasi</a><a href="{{ route('terms') }}" class="hover:text-rose-600">Syarat</a></span>
  </div>
</footer>
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
  }
</script>
@stack('scripts')
</body>
</html>
