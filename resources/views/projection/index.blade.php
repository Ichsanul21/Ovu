@extends('layouts.app')
@section('title', 'Proyeksi 5 tahun')
@section('content')
<h1 class="text-xl font-bold">Proyeksi 5 tahun</h1>
@if ($count === 0)
  <p class="mt-2 rounded-2xl bg-white p-4 text-sm shadow-sm">Catat haid dulu agar proyeksi bisa dibuat. <a href="{{ route('cycles.index') }}" class="font-semibold text-rose-600">Ke riwayat</a></p>
@else
  <p class="mt-1 text-xs text-stone-500">Berjangkar haid asli terakhir. Bergeser otomatis tiap ada data baru. Makin jauh makin tidak pasti.</p>
  @foreach ($byYear as $year => $items)
  <details class="mt-2 rounded-2xl bg-white shadow-sm" {{ $loop->first ? 'open' : '' }}>
    <summary class="cursor-pointer p-4 text-sm font-semibold">{{ $year }} ({{ count($items) }} prediksi haid)</summary>
    <ul class="space-y-1 px-4 pb-4 text-sm text-stone-600">
      @foreach ($items as $p)
        <li>Haid {{ $p['start']->translatedFormat('d M') }} . Subur {{ $p['fertile_start']->translatedFormat('d M') }}-{{ $p['fertile_end']->translatedFormat('d M') }}.</li>
      @endforeach
    </ul>
  </details>
  @endforeach
@endif
@endsection
