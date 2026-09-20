@extends('layouts.app')
@section('title', 'Mode kehamilan')
@section('content')
<h1 class="text-xl font-bold">Mode kehamilan</h1>
@if ($status)
  @php $s = $status; @endphp
  <div class="mt-2 rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-5 text-white">
    <p class="text-sm text-rose-100">Usia kehamilan</p>
    <p class="text-4xl font-bold">{{ $s['weeks'] }} minggu {{ $s['days_rest'] }} hari</p>
    <p class="mt-1 text-sm text-rose-100">Trimester {{ $s['trimester'] }} . HPL {{ $s['due']->translatedFormat('d M Y') }}</p>
    <div class="mt-2 h-2 overflow-hidden rounded-full bg-white/25"><div class="h-full rounded-full bg-white" style="width: {{ min(100, $s['weeks'] / 40 * 100) }}%"></div></div>
  </div>
  <div class="mt-3 rounded-2xl bg-white p-4 shadow-sm">
    <p class="text-sm font-semibold">Checklist trimester {{ $s['trimester'] }}</p>
    <ul class="mt-2 space-y-1.5 text-sm text-stone-600">@foreach ($s['checklist'] as $c)<li class="flex gap-2"><span class="text-rose-400">.</span><span>{{ $c }}</span></li>@endforeach</ul>
  </div>
  <a href="{{ route('logs.index') }}" class="mt-3 block rounded-2xl bg-white p-4 text-center text-sm font-semibold text-rose-600 shadow-sm">Catat gejala hari ini</a>
  @unless ($readonly)
  <form method="POST" action="{{ route('pregnancy.deactivate') }}" class="mt-3 text-center" onsubmit="return confirm('Kembali ke mode siklus?')">@csrf<button class="text-xs text-stone-400 underline">Keluar dari mode hamil</button></form>
  @endunless
@else
  <div class="mt-2 rounded-2xl bg-white p-4 text-sm shadow-sm">
    <p>Masukkan hari pertama haid terakhir (HPHT) untuk menghitung usia kehamilan dan HPL.</p>
    @unless ($readonly)
    <form method="POST" action="{{ route('pregnancy.activate') }}" class="mt-3 flex gap-2">@csrf
      <input type="date" name="hpht" required max="{{ date('Y-m-d') }}" class="flex-1 rounded-xl border px-3 py-2">
      <button class="rounded-full bg-rose-600 px-5 text-white">Aktifkan</button>
    </form>
    @endunless
  </div>
@endif
@endsection
