@extends('layouts.app')
@section('title', 'Laporan dokter')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Laporan {{ $months }} bulan</h1>
<div class="mt-3 flex gap-2 text-sm">
  @foreach ([1, 3, 6, 12] as $m)<a href="{{ route('report', ['months' => $m]) }}" class="rounded-full border px-4 py-1 {{ $months === $m ? 'bg-rose-600 text-white' : 'bg-white' }}">{{ $m }} bln</a>@endforeach
  <a href="{{ route('report.pdf', ['months' => $months]) }}" class="rounded-full bg-rose-600 px-4 py-1 text-white">Unduh PDF</a>
</div>
<div class="mt-4 rounded-2xl bg-white p-5 text-sm shadow-sm">
  <p><strong>Nama:</strong> {{ $owner->profile->full_name ?? $owner->name }}. <strong>Umur:</strong> {{ $owner->profile?->age() ?? '-' }}. <strong>Tujuan:</strong> {{ $owner->profile->goal ?? '-' }}.</p>
  <p class="mt-1"><strong>Prediksi haid berikut:</strong> {{ $prediction['next_period'] ? $prediction['next_period']->format('d M Y') : '-' }}. <strong>Masa subur:</strong> @if($prediction['fertile_start']) {{ $prediction['fertile_start']->format('d M') }} sampai {{ $prediction['fertile_end']->format('d M Y') }} @else - @endif</p>
</div>
<div class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
  <h2 class="font-semibold">Siklus ({{ $cycles->count() }})</h2>
  <ul class="mt-2 space-y-1 text-sm">@foreach ($cycles as $c)<li>{{ $c->start_date->format('d M Y') }} @if($c->end_date) sampai {{ $c->end_date->format('d M Y') }} @endif @if($c->cycle_length) (jarak {{ $c->cycle_length }} hari) @endif</li>@endforeach</ul>
</div>
<div class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
  <h2 class="font-semibold">Catatan harian ({{ $logs->count() }})</h2>
  <ul class="mt-2 space-y-1 text-sm">@foreach ($logs as $l)<li>{{ $l->log_date->format('d M y') }}. Darah {{ $l->bleeding }}. Kram {{ $l->cramp ?? '-' }}. Mood {{ $l->mood ?? '-' }}. Energi {{ $l->energy ?? '-' }}.</li>@endforeach</ul>
</div>
<p class="mt-3 text-xs text-stone-500">Insight Ovu adalah pola statistik, bukan diagnosis. Serahkan penilaian medis ke dokter atau bidan.</p>
@endsection
