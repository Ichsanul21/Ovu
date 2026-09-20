@extends('layouts.app')
@section('title', 'Kehamilanku')
@section('content')
@if ($status)
  @php $s = $status; @endphp
  <div class="rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-5 text-white">
    <p class="text-sm text-rose-100">Halo, {{ $owner->profile->full_name ?? $owner->name }}</p>
    <p class="mt-2 text-4xl font-bold">{{ $s['weeks'] }} minggu</p>
    <p class="text-sm text-rose-100">+ {{ $s['days_rest'] }} hari . Trimester {{ $s['trimester'] }}</p>
    <p class="mt-2 rounded-2xl bg-white/15 p-3 text-sm">HPL {{ $s['due']->translatedFormat('d M Y') }}. Tetap kontrol rutin ya.</p>
  </div>
  <div class="mt-3 rounded-2xl bg-white p-4 shadow-sm">
    <p class="text-sm font-semibold">Checklist minggu ini</p>
    <ul class="mt-2 space-y-1.5 text-sm text-stone-600">@foreach ($s['checklist'] as $c)<li class="flex gap-2"><span class="text-rose-400">.</span><span>{{ $c }}</span></li>@endforeach</ul>
  </div>
  <div class="mt-3 grid grid-cols-2 gap-2">
    <a href="{{ route('logs.index') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-semibold text-rose-600 shadow-sm">Catat gejala</a>
    <a href="{{ route('pregnancy.index') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-semibold text-rose-600 shadow-sm">Detail</a>
  </div>
@else
  <p class="rounded-2xl bg-white p-4 text-sm shadow-sm">Belum ada data kehamilan. <a href="{{ route('pregnancy.index') }}" class="font-semibold text-rose-600">Aktifkan di sini</a></p>
@endif
@endsection
