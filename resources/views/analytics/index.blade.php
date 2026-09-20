@extends('layouts.app')
@section('title', 'Wawasan Ovu')
@section('content')
<h1 class="text-xl font-bold">Wawasan</h1>
<p class="mt-1 text-xs text-stone-500">Rata-rata {{ $prediction['avg_cycle'] }} hari . Keyakinan {{ $prediction['confidence'] }}.</p>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold">Panjang siklus (hari)</p>
  <canvas id="cycleChart" class="mt-2 h-48 w-full"></canvas>
</div>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold">Energi dan kram 90 hari terakhir</p>
  <canvas id="symptomChart" class="mt-2 h-48 w-full"></canvas>
</div>
<div class="mt-2 space-y-2">
  @foreach ($insights as $i)
    <div class="rounded-2xl border p-3 text-sm shadow-sm {{ $i['level'] === 'waspada' ? 'border-amber-300 bg-amber-50' : 'border-rose-100 bg-white' }}">
      <p class="font-semibold">{{ $i['title'] }}</p><p class="mt-0.5 text-stone-600">{{ $i['body'] }}</p>
    </div>
  @endforeach
</div>
<a href="{{ route('report') }}" class="mt-2 block rounded-2xl bg-rose-600 p-3 text-center text-sm font-semibold text-white">Laporan untuk dokter</a>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
<script>
  const lengths = @json($lengths);
  new Chart(document.getElementById('cycleChart'), {type: 'line', data: {labels: lengths.map(x => x.label), datasets: [{data: lengths.map(x => x.length), borderColor: '#e11d48', tension: 0.3}]}, options: {plugins: {legend: {display: false}}, scales: {y: {suggestedMin: 21, suggestedMax: 40}}}}});
  const logs = @json($logs->map(fn($l) => ['d' => $l->log_date->format('d M'), 'e' => $l->energy, 'k' => $l->cramp]));
  new Chart(document.getElementById('symptomChart'), {type: 'bar', data: {labels: logs.map(x => x.d), datasets: [{label: 'Energi', data: logs.map(x => x.e), backgroundColor: '#34d399'}, {label: 'Kram', data: logs.map(x => x.k), backgroundColor: '#fb7185'}]}, options: {scales: {y: {suggestedMax: 5}}}}});
</script>
@endpush
@endsection
