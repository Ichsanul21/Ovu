<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><style>body{font-family:sans-serif;font-size:12px;color:#222}h1{color:#be123c}table{width:100%;border-collapse:collapse;margin-top:8px}td,th{border:1px solid #ccc;padding:4px 6px;text-align:left}</style></head>
<body>
<h1>Laporan Ovu ({{ $months }} bulan)</h1>
<p>Nama: {{ $owner->profile->full_name ?? $owner->name }}. Umur: {{ $owner->profile?->age() ?? '-' }}. Tujuan: {{ $owner->profile->goal ?? '-' }}.</p>
<p>Prediksi haid berikut: {{ $prediction['next_period'] ? $prediction['next_period']->format('d M Y') : '-' }}. Rata-rata siklus: {{ $prediction['avg_cycle'] }} hari.</p>
<h2>Siklus</h2>
<table><tr><th>Mulai</th><th>Selesai</th><th>Jarak (hari)</th></tr>
@foreach ($cycles as $c)<tr><td>{{ $c->start_date->format('d M Y') }}</td><td>{{ $c->end_date ? $c->end_date->format('d M Y') : '-' }}</td><td>{{ $c->cycle_length ?? '-' }}</td></tr>@endforeach
</table>
<h2>Catatan harian</h2>
<table><tr><th>Tanggal</th><th>Darah</th><th>Kram</th><th>Mood</th><th>Energi</th></tr>
@foreach ($logs as $l)<tr><td>{{ $l->log_date->format('d M Y') }}</td><td>{{ $l->bleeding }}</td><td>{{ $l->cramp ?? '-' }}</td><td>{{ $l->mood ?? '-' }}</td><td>{{ $l->energy ?? '-' }}</td></tr>@endforeach
</table>
<p>Catatan: pola statistik dari catatan pengguna, bukan diagnosis medis.</p>
</body></html>
