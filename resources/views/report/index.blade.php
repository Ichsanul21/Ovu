@extends('layouts.app')
@section('title', 'Laporan dokter')
@section('content')
<h1 class="text-xl font-bold">Laporan {{ $months }} bulan</h1>
<div class="mt-2 flex gap-1.5 text-xs">
  @foreach ([1, 3, 6, 12] as $m)<a href="{{ route('report', ['months' => $m]) }}" class="rounded-full border px-3 py-1 {{ $months === $m ? 'bg-rose-600 text-white' : 'bg-white' }}">{{ $m }} bln</a>@endforeach
  <a href="{{ route('report.pdf', ['months' => $months]) }}" class="rounded-full bg-rose-600 px-3 py-1 text-white">PDF</a>
</div>
<div class="mt-2 rounded-2xl bg-white p-4 text-sm shadow-sm">
  <p><strong>{{ $owner->profile->full_name ?? $owner->name }}</strong> . Umur {{ $owner->profile?->age() ?? '-' }} . Tujuan {{ $owner->profile->goal ?? '-' }}.</p>
  <p class="mt-1">Haid berikut: <strong>{{ $prediction['next_period'] ? $prediction['next_period']->translatedFormat('d M Y') : '-' }}</strong> . Subur: @if($prediction['fertile_start']) {{ $prediction['fertile_start']->translatedFormat('d M') }}-{{ $prediction['fertile_end']->translatedFormat('d M Y') }} @else - @endif</p>
</div>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold">Siklus ({{ $cycles->count() }})</p>
  <ul class="mt-1 space-y-1 text-sm text-stone-600">@foreach ($cycles as $c)<li>{{ $c->start_date->translatedFormat('d M Y') }} @if($c->end_date) sampai {{ $c->end_date->translatedFormat('d M Y') }} @endif @if($c->cycle_length) ({{ $c->cycle_length }} hari) @endif</li>@endforeach</ul>
</div>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold">Catatan ({{ $logs->count() }})</p>
  <ul class="mt-1 space-y-1 text-sm text-stone-600">@foreach ($logs as $l)<li>{{ $l->log_date->translatedFormat('d M y') }} . darah {{ $l->bleeding }} . kram {{ $l->cramp ?? '-' }} . mood {{ $l->mood ?? '-' }} . energi {{ $l->energy ?? '-' }}.</li>@endforeach</ul>
</div>
<p class="mt-2 text-[11px] text-stone-400">Pola statistik dari catatan, bukan diagnosis. Serahkan penilaian ke dokter atau bidan.</p>
@endsection
