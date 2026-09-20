<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#f43f5e">
<title>@yield('title', 'Ovu')</title>
<link rel="manifest" href="/manifest.webmanifest">
<link rel="icon" href="/icons/icon.svg" type="image/svg+xml">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-rose-100 text-stone-800 antialiased">
<div class="mx-auto flex min-h-screen w-full max-w-md flex-col bg-rose-50 shadow-xl">
  @auth
  <header class="sticky top-0 z-20 border-b border-rose-100 bg-white/95 backdrop-blur">
    <div class="flex items-center justify-between px-4 py-2.5">
      <a href="{{ route('settings') }}" class="flex h-9 w-9 items-center justify-center rounded-full bg-rose-100 font-bold text-rose-700">{{ mb_substr(auth()->user()->name, 0, 1) }}</a>
      <a href="{{ route('dashboard') }}" class="text-lg font-bold text-rose-600">Ovu</a>
      <a href="{{ route('calendar') }}" class="flex h-9 w-9 items-center justify-center rounded-full bg-rose-100 text-rose-700" aria-label="Kalender">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      </a>
    </div>
  </header>
  @endauth
  <main class="flex-1 px-4 pb-28 pt-4">
    @if (session('ok'))
      <div class="mb-3 rounded-2xl border border-rose-200 bg-white px-4 py-3 text-sm text-rose-800">{{ session('ok') }}</div>
    @endif
    @if ($errors->any())
      <div class="mb-3 rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-red-700">
        <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif
    @yield('content')
  </main>
  @auth
  <nav class="fixed bottom-0 left-1/2 z-20 w-full max-w-md -translate-x-1/2 border-t border-rose-100 bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur">
    <div class="grid grid-cols-5 items-end px-2 pb-2 pt-1 text-[11px]">
      <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-0.5 py-1 {{ request()->routeIs('dashboard') ? 'text-rose-600' : 'text-stone-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>Beranda
      </a>
      <a href="{{ route('calendar') }}" class="flex flex-col items-center gap-0.5 py-1 {{ request()->routeIs('calendar') ? 'text-rose-600' : 'text-stone-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Kalender
      </a>
      <a href="{{ route('logs.index') }}" class="flex flex-col items-center" aria-label="Catat">
        <span class="flex h-14 w-14 -translate-y-4 items-center justify-center rounded-full bg-rose-600 text-3xl text-white shadow-lg">+</span>
      </a>
      <a href="{{ route('analytics') }}" class="flex flex-col items-center gap-0.5 py-1 {{ request()->routeIs('analytics') ? 'text-rose-600' : 'text-stone-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Wawasan
      </a>
      <a href="{{ route('settings') }}" class="flex flex-col items-center gap-0.5 py-1 {{ request()->routeIs('settings') ? 'text-rose-600' : 'text-stone-400' }}">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>Menu
      </a>
    </div>
  </nav>
  @endauth
  <footer class="px-4 pb-24 pt-2 text-center text-[11px] text-stone-400 @auth hidden @endauth">
    <a href="{{ route('privacy') }}" class="underline">Privasi</a> . <a href="{{ route('terms') }}" class="underline">Syarat</a> . Data milikmu, bukan untuk dijual.
  </footer>
</div>
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
  }
</script>
@stack('scripts')
</body>
</html>
