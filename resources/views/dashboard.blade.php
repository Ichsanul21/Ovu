@extends('layouts.app')
@section('title', 'Dasbor Ovu')
@section('content')
@php
  $phaseLabel = ['menstruasi' => 'Menstruasi', 'folikuler' => 'Folikuler', 'ovulasi' => 'Ovulasi', 'luteal' => 'Luteal', 'unknown' => 'Belum ada data'][$phase] ?? $phase;
  $goalLabel = ['promil' => 'Program hamil', 'kb' => 'Menunda kehamilan', 'kesehatan' => 'Pantau kesehatan'][$goal] ?? $goal;
@endphp
<div class="rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-6 text-white">
  <div class="flex items-center justify-between text-sm text-rose-100">
    <span>Halo, {{ $owner->profile->full_name ?? $owner->name }}</span>
    @if ($owner->profile?->zodiac())<span>{{ $owner->profile->zodiac() }} (just for fun)</span>@endif
  </div>
  @if ($prediction['cycle_day'])
    <p class="mt-3 text-4xl font-bold">Hari ke-{{ $prediction['cycle_day'] }}</p>
    <p class="mt-1 text-rose-100">Fase {{ $phaseLabel }}. {{ $goalLabel }}.</p>
  @else
    <p class="mt-3 text-2xl font-bold">Mulai dengan mencatat haid pertamamu.</p>
    <p class="mt-1 text-rose-100">Tambahkan riwayat haid agar prediksi berjalan.</p>
  @endif
  <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
    <div class="rounded-2xl bg-white/15 p-3"><p class="text-rose-100">Perkiraan haid</p><p class="font-semibold">{{ $prediction['next_period'] ? $prediction['next_period']->format('d M Y') : '-' }}</p></div>
    <div class="rounded-2xl bg-white/15 p-3"><p class="text-rose-100">Masa subur</p><p class="font-semibold">@if($prediction['fertile_start']) {{ $prediction['fertile_start']->format('d M') }} sampai {{ $prediction['fertile_end']->format('d M') }} @else - @endif</p></div>
    <div class="rounded-2xl bg-white/15 p-3"><p class="text-rose-100">Ovulasi</p><p class="font-semibold">{{ $prediction['ovulation_date'] ? $prediction['ovulation_date']->format('d M Y') : '-' }}</p></div>
    <div class="rounded-2xl bg-white/15 p-3"><p class="text-rose-100">Keyakinan</p><p class="font-semibold">{{ $prediction['confidence'] }} ({{ $prediction['avg_cycle'] }} hari)</p></div>
  </div>
  @if ($prediction['late'])
    <p class="mt-3 rounded-xl bg-white px-3 py-2 text-sm font-semibold text-rose-700">Haid terlambat dari prediksi. Catat gejala dan pertimbangkan tes bila perlu.</p>
  @endif
  @if ($bmi)<p class="mt-3 text-sm text-rose-100">BMI {{ $bmi }} ({{ $bmiCategory }}).</p>@endif
</div>

@if ($tip)
<div class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
  <h2 class="font-semibold text-rose-700">Tips hari ini. Fase {{ $phaseLabel }}</h2>
  <p class="mt-1 text-sm">{{ $tip['kondisi'] }}</p>
  <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-stone-700">@foreach ($tip['tips'] as $t)<li>{{ $t }}</li>@endforeach</ul>
</div>
@endif

<div class="mt-4 grid gap-3 sm:grid-cols-2">
  @unless ($readonly)
  <form method="POST" action="{{ route('cycles.today') }}" class="rounded-2xl bg-white p-4 shadow-sm">@csrf<button class="w-full rounded-full bg-rose-600 py-2 font-semibold text-white">Catat haid hari ini (1 ketuk)</button></form>
  @endunless
  <a href="{{ route('logs.index') }}" class="rounded-2xl bg-white p-4 text-center shadow-sm"><span class="font-semibold text-rose-700">{{ $todayLog ? 'Lihat catatan hari ini' : 'Isi catatan hari ini' }}</span><span class="block text-xs text-stone-500">Butuh waktu sekitar 30 detik</span></a>
</div>
<div class="mt-3 grid gap-3 sm:grid-cols-3">
  <a href="{{ route('calendar') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Kalender</a>
  <a href="{{ route('cycles.index') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Riwayat haid</a>
  <a href="{{ route('report') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Laporan dokter</a>
</div>
@if ($readonly)<p class="mt-3 text-center text-xs text-stone-500">Kamu masuk sebagai pasangan (hanya lihat).</p>@endif
@endsection
