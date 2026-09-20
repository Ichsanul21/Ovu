@extends('layouts.app')
@section('title', 'Kalender Ovu')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Kalender</h1>
@php
  $prev = $cursor->copy()->subMonth()->format('Y-m');
  $next = $cursor->copy()->addMonth()->format('Y-m');
  $labels = ['haid' => 'bg-rose-500 text-white', 'prediksi_haid' => 'bg-rose-200', 'ovulasi' => 'bg-amber-300', 'subur' => 'bg-emerald-200', 'biasa' => 'bg-white'];
@endphp
<div class="mt-3 flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">
  <a href="{{ route('calendar', ['month' => $prev]) }}" class="rounded-full border px-4 py-1">Kembali</a>
  <p class="font-semibold">{{ $cursor->format('F Y') }}</p>
  <a href="{{ route('calendar', ['month' => $next]) }}" class="rounded-full border px-4 py-1">Lanjut</a>
</div>
<div class="mt-3 grid grid-cols-7 gap-1 text-center text-xs font-medium text-stone-500">
  <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
</div>
<div class="mt-1 grid grid-cols-7 gap-1">
  @foreach ($cells as $c)
    <div class="rounded-xl p-2 text-center text-sm {{ $labels[$c['kind']] }} {{ $c['in_month'] ? '' : 'opacity-40' }} {{ $c['is_today'] ? 'ring-2 ring-rose-600' : '' }}">
      <span>{{ $c['date']->day }}</span>
      @if ($c['has_log'])<span class="block text-[10px]">ada catatan</span>@endif
    </div>
  @endforeach
</div>
<div class="mt-3 flex flex-wrap gap-2 text-xs">
  <span class="rounded-full bg-rose-500 px-3 py-1 text-white">Haid</span>
  <span class="rounded-full bg-rose-200 px-3 py-1">Prediksi haid</span>
  <span class="rounded-full bg-emerald-200 px-3 py-1">Masa subur</span>
  <span class="rounded-full bg-amber-300 px-3 py-1">Ovulasi</span>
</div>
@endsection
