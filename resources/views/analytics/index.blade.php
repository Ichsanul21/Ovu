@extends('layouts.app')
@section('title', 'Analitik Ovu')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Analitik</h1>
<p class="mt-1 text-sm text-stone-600">Rata-rata {{ $prediction['avg_cycle'] }} hari. Keyakinan {{ $prediction['confidence'] }}.</p>
<div class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
  <h2 class="font-semibold">Panjang siklus (hari)</h2>
  <canvas id="cycleChart" class="mt-3 h-56 w-full"></canvas>
</div>
<div class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
  <h2 class="font-semibold">Energi dan kram 90 hari terakhir</h2>
  <canvas id="symptomChart" class="mt-3 h-56 w-full"></canvas>
</div>
<div class="mt-4 space-y-3">
  <h2 class="font-semibold text-rose-700">Insight pola</h2>
  @foreach ($insights as $i)
    <div class="rounded-2xl border p-4 text-sm shadow-sm {{ $i['level'] === 'waspada' ? 'border-amber-300 bg-amber-50' : 'border-rose-100 bg-white' }}">
      <p class="font-semibold">{{ $i['title'] }}</p><p class="mt-1 text-stone-700">{{ $i['body'] }}</p>
    </div>
  @endforeach
</div>
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
