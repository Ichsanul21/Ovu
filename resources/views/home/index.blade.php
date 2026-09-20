@extends('layouts.app')
@section('title', 'Beranda Ovu')
@section('content')
@php
  $phaseLabel = ['menstruasi' => 'Menstruasi', 'folikuler' => 'Folikuler', 'ovulasi' => 'Ovulasi', 'luteal' => 'Luteal', 'unknown' => 'Belum ada data'][$phase] ?? $phase;
  $goalLabel = ['promil' => 'Program hamil', 'kb' => 'Menunda kehamilan', 'kesehatan' => 'Pantau kesehatan', 'hamil' => 'Kehamilan'][$goal] ?? $goal;
  $day = $prediction['cycle_day'];
  $avg = max(1, (int) round($prediction['avg_cycle']));
  $progress = $day ? min(1, $day / $avg) : 0;
  $ring = 2 * pi() * 84;
  $dash = $ring * $progress;
  $dow = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
@endphp

<div class="rounded-3xl bg-white p-5 shadow-sm">
  <p class="text-center text-sm text-stone-500">Halo, {{ $owner->profile->full_name ?? $owner->name }}</p>

  @if ($day)
  <div class="relative mx-auto mt-2 h-52 w-52">
    <svg viewBox="0 0 200 200" class="h-full w-full -rotate-90">
      <circle cx="100" cy="100" r="84" fill="none" stroke="#ffe4e6" stroke-width="16"/>
      <circle cx="100" cy="100" r="84" fill="none" stroke="#f43f5e" stroke-width="16" stroke-linecap="round" stroke-dasharray="{{ $dash }} {{ $ring }}"/>
    </svg>
    <div class="absolute inset-0 flex flex-col items-center justify-center">
      <p class="text-4xl font-bold text-stone-800">{{ $day }}</p>
      <p class="text-xs text-stone-500">hari ke dari {{ $avg }}</p>
      <p class="mt-1 rounded-full bg-rose-100 px-3 py-0.5 text-xs font-semibold text-rose-700">{{ $phaseLabel }}</p>
      <p class="mt-1 text-sm font-medium text-stone-700">{{ $countdown['label'] }}</p>
    </div>
  </div>
  @else
  <div class="mx-auto mt-2 flex h-52 w-52 flex-col items-center justify-center rounded-full border-[16px] border-rose-100 text-center">
    <p class="px-6 text-sm font-medium">Catat haid terakhirmu untuk menyalakan prediksi.</p>
  </div>
  @endif

  @if ($day)
  <div class="mt-3 rounded-2xl bg-rose-50 p-3">
    <div class="flex items-center justify-between text-xs">
      <span class="font-semibold text-stone-700">Peluang hamil: {{ $chance['label'] }}</span>
      <span class="text-stone-500">{{ $chance['percent'] }} persen</span>
    </div>
    <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-rose-100">
      <div class="h-full rounded-full bg-rose-500" style="width: {{ max(4, $chance['percent']) }}%"></div>
    </div>
  </div>
  @endif

  <div class="mt-3 grid {{ $stripDays === 14 ? 'grid-cols-7' : 'grid-cols-7' }} gap-1 text-center">
    @foreach ($strip as $s)
      <a href="{{ route('logs.index', ['date' => $s['date']->toDateString()]) }}" class="rounded-xl py-1.5 {{ $s['is_today'] ? 'bg-rose-600 text-white' : 'bg-rose-50 text-stone-600' }}">
        <span class="block text-[10px]">{{ $dow[$s['date']->dayOfWeek] }}</span>
        <span class="block text-sm font-semibold">{{ $s['date']->day }}</span>
      </a>
    @endforeach
  </div>

  @unless ($readonly)
  <form method="POST" action="{{ route('cycles.today') }}" class="mt-3">@csrf
    <button class="w-full rounded-full bg-rose-600 py-3 font-semibold text-white">Catat Haid</button>
  </form>
  @endunless
</div>

@if ($pill)
<div class="mt-3 flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">
  <div><p class="text-sm font-semibold">Pil KB jam {{ $pill['time'] }}</p><p class="text-xs text-stone-500">{{ $pill['taken'] ? 'Sudah diminum hari ini. Hebat.' : 'Belum ditandai hari ini.' }}</p></div>
  @unless ($readonly)
  <form method="POST" action="{{ route('pill.toggle') }}">@csrf<button class="rounded-full {{ $pill['taken'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-600 text-white' }} px-5 py-2 text-sm font-semibold">{{ $pill['taken'] ? 'Sudah' : 'Minum' }}</button></form>
  @endunless
</div>
@endif

@if ($tip)
<div class="mt-3 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-xs font-semibold uppercase tracking-wide text-rose-500">Cerita hari ini</p>
  <p class="mt-1 text-sm">{{ $tip['kondisi'] }}</p>
  <ul class="mt-2 space-y-1 text-sm text-stone-600">@foreach (array_slice($tip['tips'], 0, 3) as $t)<li class="flex gap-2"><span class="text-rose-400">.</span><span>{{ $t }}</span></li>@endforeach</ul>
</div>
@endif

@foreach ($cards as $c)
<div class="mt-3 rounded-2xl border border-rose-100 bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold text-stone-800">{{ $c['title'] }}</p>
  <p class="mt-1 text-sm text-stone-600">{{ $c['body'] }}</p>
  @if ($c['action_label'])<a href="{{ route($c['action_route']) }}" class="mt-2 inline-block text-sm font-semibold text-rose-600">{{ $c['action_label'] }} &rsaquo;</a>@endif
</div>
@endforeach

<div class="mt-3 rounded-2xl bg-white p-4 shadow-sm">
  <div class="flex items-center justify-between">
    <p class="text-sm font-semibold">Siklus saya</p>
    <a href="{{ route('cycles.index') }}" class="text-xs font-semibold text-rose-600">Riwayat &rsaquo;</a>
  </div>
  @if ($cycleStats['avg'])
    <p class="mt-1 text-sm text-stone-600">Rata-rata {{ $cycleStats['avg'] }} hari dari {{ $cycleStats['count'] }} catatan.</p>
    <p class="mt-1 text-sm">Panjang: <strong class="{{ $cycleStats['normal'] ? 'text-emerald-600' : 'text-amber-600' }}">{{ $cycleStats['normal'] ? 'Normal (21-35 hari)' : 'Di luar 21-35 hari' }}</strong></p>
    @if ($cycleStats['regular'] !== null)
    <p class="text-sm">Keteraturan: <strong class="{{ $cycleStats['regular'] ? 'text-emerald-600' : 'text-amber-600' }}">{{ $cycleStats['regular'] ? 'Teratur' : 'Bervariasi' }}</strong></p>
    @endif
  @else
    <p class="mt-1 text-sm text-stone-600">Tambahkan haid 3 siklus terakhir agar status siklusmu terbaca. Dari ingatan pun boleh.</p>
    <a href="{{ route('cycles.index') }}" class="mt-2 inline-block rounded-full bg-rose-100 px-4 py-1.5 text-sm font-semibold text-rose-700">Tambah riwayat</a>
  @endif
</div>

@if ($readonly)<p class="mt-3 text-center text-xs text-stone-400">Kamu masuk sebagai pasangan (hanya lihat).</p>@endif
@endsection
