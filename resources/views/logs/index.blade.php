@extends('layouts.app')
@section('title', 'Catatan harian')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Catatan {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h1>
<form method="GET" action="{{ route('logs.index') }}" class="mt-3 flex gap-2">
  <input type="date" name="date" value="{{ $date }}" class="rounded-xl border px-3 py-2">
  <button class="rounded-full border px-4">Lihat</button>
</form>
@if ($readonly)
  <div class="mt-4 rounded-2xl bg-white p-5 text-sm shadow-sm">
    @if ($log)
      <p>Darah: level {{ $log->bleeding }}. Mood: {{ $log->mood ?? '-' }}. Energi: {{ $log->energy ?? '-' }}.</p>
    @else
      <p>Belum ada catatan hari ini.</p>
    @endif
  </div>
@else
<form method="POST" action="{{ route('logs.store') }}" class="mt-4 space-y-4 rounded-2xl bg-white p-5 shadow-sm">
  @csrf
  <input type="hidden" name="log_date" value="{{ $date }}">
  <div>
    <p class="text-sm font-medium">Darah haid</p>
    <div class="mt-1 flex flex-wrap gap-2 text-sm">
      @foreach ([0 => 'Tidak ada', 1 => 'Bercak', 2 => 'Sedikit', 3 => 'Sedang', 4 => 'Deras'] as $v => $l)
        <label class="rounded-full border px-3 py-1"><input type="radio" name="bleeding" value="{{ $v }}" {{ (int) old('bleeding', $log->bleeding ?? 0) === $v ? 'checked' : '' }}> {{ $l }}</label>
      @endforeach
    </div>
  </div>
  <div class="grid gap-3 sm:grid-cols-2">
    <div><label class="text-sm">Kram (1 ringan sampai 5 berat)</label><input type="number" name="cramp" min="1" max="5" value="{{ old('cramp', $log->cramp ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm">Sakit kepala (1-5)</label><input type="number" name="headache" min="1" max="5" value="{{ old('headache', $log->headache ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm">Nyeri payudara (1-5)</label><input type="number" name="breast_pain" min="1" max="5" value="{{ old('breast_pain', $log->breast_pain ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm">Jerawat (1-5)</label><input type="number" name="acne" min="1" max="5" value="{{ old('acne', $log->acne ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  </div>
  <div class="grid gap-3 sm:grid-cols-3">
    <div><label class="text-sm">Mood</label><select name="mood" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="">Pilih</option>@foreach (['senang','tenang','sensitif','cemas','sedih','marah','lelahan'] as $m)<option value="{{ $m }}" {{ old('mood', $log->mood ?? '') === $m ? 'selected' : '' }}>{{ $m }}</option>@endforeach</select></div>
    <div><label class="text-sm">Energi (1-5)</label><input type="number" name="energy" min="1" max="5" value="{{ old('energy', $log->energy ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm">Tidur (jam)</label><input type="number" step="0.5" name="sleep_hours" value="{{ old('sleep_hours', $log->sleep_hours ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  </div>
  <div class="grid gap-3 sm:grid-cols-2">
    <div><label class="text-sm">Lendir serviks</label><select name="cervical_fluid" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="">Pilih</option>@foreach (['kering'=>'Kering','lengket'=>'Lengket','creamy'=>'Creamy','putih_telur'=>'Jernih licin (putih telur)'] as $v=>$l)<option value="{{ $v }}" {{ old('cervical_fluid', $log->cervical_fluid ?? '') === $v ? 'selected' : '' }}>{{ $l }}</option>@endforeach</select></div>
    <div><label class="text-sm">Suhu basal (C)</label><input type="number" step="0.1" name="bbt" value="{{ old('bbt', $log->bbt ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Contoh: 36.6"></div>
    <div><label class="text-sm">Tes ovulasi (LH)</label><select name="lh_test" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="tidak_tes">Tidak tes</option><option value="negatif">Negatif</option><option value="positif">Positif</option></select></div>
    <div><label class="text-sm">Testpack</label><select name="testpack" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="tidak_tes">Tidak tes</option><option value="negatif">Negatif</option><option value="samar">Samar</option><option value="positif">Positif</option></select></div>
  </div>
  <div class="flex flex-wrap gap-4 text-sm">
    <label class="flex items-center gap-2"><input type="checkbox" name="intercourse" value="1" {{ old('intercourse', $log->intercourse ?? false) ? 'checked' : '' }}>Hubungan intim</label>
    <label class="flex items-center gap-2"><input type="checkbox" name="protected" value="1" {{ old('protected', $log->protected ?? false) ? 'checked' : '' }}>Dengan proteksi</label>
  </div>
  <div><label class="text-sm">Berat badan hari ini (kg, opsional)</label><input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $log->weight_kg ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div><label class="text-sm">Diary bebas</label><textarea name="diary" rows="3" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Cerita harimu di sini...">{{ old('diary', $log->diary ?? '') }}</textarea></div>
  <button class="w-full rounded-full bg-rose-600 py-2.5 font-semibold text-white">Simpan catatan</button>
</form>
@endif
@if ($recent->isNotEmpty())
<h2 class="mt-6 font-semibold text-rose-700">14 hari terakhir</h2>
<div class="mt-2 space-y-2">@foreach ($recent as $r)<a href="{{ route('logs.index', ['date' => $r->log_date->format('Y-m-d')]) }}" class="block rounded-2xl bg-white p-3 text-sm shadow-sm">{{ $r->log_date->format('d M') }}. Darah {{ $r->bleeding }}. Mood {{ $r->mood ?? '-' }}. Kram {{ $r->cramp ?? '-' }}.</a>@endforeach</div>
@endif
@endsection
