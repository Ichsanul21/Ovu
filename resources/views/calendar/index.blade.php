@extends('layouts.app')
@section('title', 'Kalender Ovu')
@section('content')
<h1 class="text-xl font-bold">Kalender</h1>
@php
  $prev = $cursor->copy()->subMonth()->format('Y-m');
  $next = $cursor->copy()->addMonth()->format('Y-m');
  $canNext = $cursor->copy()->addMonth()->lte($maxFuture);
  $styles = [
    'haid' => 'bg-rose-500 text-white',
    'prediksi_haid' => 'text-rose-600 border-2 border-dashed border-rose-400',
    'ovulasi' => 'text-teal-700 border-2 border-dashed border-teal-500 bg-teal-50',
    'subur' => 'bg-teal-100 text-teal-800',
    'luteal' => 'bg-stone-200 text-stone-600',
    'biasa' => 'bg-white',
  ];
@endphp
<div class="mt-2 flex items-center justify-between rounded-2xl bg-white p-3 shadow-sm">
  <a href="{{ route('calendar', ['month' => $prev]) }}" class="rounded-full border px-4 py-1 text-sm">Kembali</a>
  <p class="text-sm font-semibold">{{ $cursor->translatedFormat('F Y') }}</p>
  @if ($canNext)<a href="{{ route('calendar', ['month' => $next]) }}" class="rounded-full border px-4 py-1 text-sm">Lanjut</a>
  @else<span class="rounded-full bg-stone-100 px-4 py-1 text-sm text-stone-400">Mentok 5 thn</span>@endif
</div>
<div class="mt-2 flex items-center justify-between text-xs">
  <div class="flex flex-wrap gap-1.5">
    <span class="rounded-full bg-rose-500 px-2 py-0.5 text-white">Haid</span>
    <span class="rounded-full border border-dashed border-rose-400 px-2 py-0.5 text-rose-600">Prediksi</span>
    <span class="rounded-full bg-teal-100 px-2 py-0.5 text-teal-800">Subur</span>
    <span class="rounded-full border border-dashed border-teal-500 px-2 py-0.5 text-teal-700">Ovulasi</span>
  </div>
  @unless ($readonly)
  <a href="{{ route('calendar', ['month' => $cursor->format('Y-m'), 'edit' => $edit ? 0 : 1]) }}" class="font-semibold text-rose-600">{{ $edit ? 'Selesai' : 'Edit haid' }}</a>
  @endunless
</div>
@if ($edit)
<form method="POST" action="{{ route('cycles.store') }}" class="mt-2 flex gap-2 rounded-2xl bg-white p-3 text-sm shadow-sm">
  @csrf
  <input type="date" name="start_date" required max="{{ date('Y-m-d') }}" class="flex-1 rounded-xl border px-2 py-1.5">
  <input type="date" name="end_date" class="flex-1 rounded-xl border px-2 py-1.5">
  <button class="rounded-full bg-rose-600 px-4 text-white">Simpan</button>
</form>
@endif
<div class="mt-2 grid grid-cols-7 gap-1 text-center text-[11px] font-medium text-stone-400">
  <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
</div>
<div class="mt-1 grid grid-cols-7 gap-1">
  @foreach ($cells as $c)
    <a href="{{ route('logs.index', ['date' => $c['date']->toDateString()]) }}" class="rounded-xl p-1.5 text-center text-sm {{ $styles[$c['kind']] }} {{ $c['in_month'] ? '' : 'opacity-40' }} {{ $c['is_today'] ? 'ring-2 ring-rose-600' : '' }}">
      <span>{{ $c['date']->day }}</span>
      @if ($c['has_log'])<span class="mx-auto block h-1 w-1 rounded-full bg-current"></span>@endif
    </a>
  @endforeach
</div>
<p class="mt-2 text-center text-[11px] text-stone-400">Tanggal pudar = proyeksi yang menyesuaikan tiap ada haid asli tercatat.</p>
@endsection
