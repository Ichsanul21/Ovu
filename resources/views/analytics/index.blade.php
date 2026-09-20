@extends('layouts.app')
@section('title', 'Wawasan Ovu')
@section('content')
<h1 class="text-xl font-bold">Wawasan</h1>
<p class="mt-1 text-xs text-stone-500">Rata-rata {{ $prediction['avg_cycle'] }} hari . Keyakinan {{ $prediction['confidence'] }}.</p>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold">Panjang siklus (hari)</p>
  @if (empty($cycleValues))
    <p class="mt-2 text-sm text-stone-500">Belum ada data. Tambahkan minimal 2 tanggal haid di Riwayat.</p>
  @else
    <canvas id="cycleChart" class="mt-2 h-48 w-full"></canvas>
  @endif
</div>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <p class="text-sm font-semibold">Energi dan kram 90 hari terakhir</p>
  @if (empty($logLabels))
    <p class="mt-2 text-sm text-stone-500">Belum ada catatan. Ketuk + untuk mencatat hari ini.</p>
  @else
    <canvas id="symptomChart" class="mt-2 h-48 w-full"></canvas>
  @endif
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
<script type="application/json" id="ovu-chart-data">{!! json_encode(['cycleLabels' => $cycleLabels, 'cycleValues' => $cycleValues, 'logLabels' => $logLabels, 'logEnergy' => $logEnergy, 'logCramp' => $logCramp], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  var el = document.getElementById('ovu-chart-data');
  if (!el) return;
  var data = JSON.parse(el.textContent);
  if (typeof Chart === 'undefined') return;
  var cycleCanvas = document.getElementById('cycleChart');
  if (cycleCanvas && data.cycleValues.length > 0) {
    new Chart(cycleCanvas, {type: 'line', data: {labels: data.cycleLabels, datasets: [{data: data.cycleValues, borderColor: '#e11d48', tension: 0.3}]}, options: {plugins: {legend: {display: false}}, scales: {y: {suggestedMin: 21, suggestedMax: 40}}}}});
  }
  var symptomCanvas = document.getElementById('symptomChart');
  if (symptomCanvas && data.logLabels.length > 0) {
    new Chart(symptomCanvas, {type: 'bar', data: {labels: data.logLabels, datasets: [{label: 'Energi', data: data.logEnergy, backgroundColor: '#34d399'}, {label: 'Kram', data: data.logCramp, backgroundColor: '#fb7185'}]}, options: {scales: {y: {suggestedMax: 5}}}}});
  }
})();
</script>
@endpush
@endsection
