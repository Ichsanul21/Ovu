@extends('layouts.app')
@section('title', 'Lengkapi profil')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Kenalan dulu yuk</h1>
<p class="mt-1 text-sm text-stone-600">Data ini dipakai untuk analisis BMI, konteks usia, dan pola kesehatan. Bisa diubah kapan saja.</p>
<form method="POST" action="{{ route('onboarding.store') }}" class="mt-5 space-y-4 rounded-2xl bg-white p-6 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama lengkap</label><input name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div class="grid gap-4 sm:grid-cols-3">
    <div><label class="text-sm font-medium">Tanggal lahir</label><input type="date" name="birth_date" value="{{ old('birth_date', isset($profile->birth_date) ? $profile->birth_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm font-medium">Tinggi (cm)</label><input type="number" name="height_cm" value="{{ old('height_cm', $profile->height_cm ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm font-medium">Berat awal (kg)</label><input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $profile->weight_kg ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  </div>
  <div>
    <p class="text-sm font-medium">Penyakit bawaan (boleh pilih lebih dari satu)</p>
    @php $conds = old('conditions', isset($profile) ? $profile->conditionsList() : []); @endphp
    <div class="mt-2 grid grid-cols-2 gap-2 text-sm">
      @foreach (['PCOS', 'Endometriosis', 'Tiroid', 'Anemia', 'Diabetes', 'Hipertensi'] as $c)
        <label class="flex items-center gap-2 rounded-xl border px-3 py-2"><input type="checkbox" name="conditions[]" value="{{ $c }}" {{ in_array($c, $conds) ? 'checked' : '' }}>{{ $c }}</label>
      @endforeach
    </div>
    <input name="conditions_other" placeholder="Lainnya, contoh: asma" class="mt-2 w-full rounded-xl border px-3 py-2">
  </div>
  <div class="grid gap-4 sm:grid-cols-2">
    <div><label class="text-sm font-medium">Obat rutin</label><input name="routine_meds" value="{{ old('routine_meds', $profile->routine_meds ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Opsional"></div>
    <div><label class="text-sm font-medium">Riwayat KB</label><select name="kb_history" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="">Belum ada</option><option>Pil</option><option>Suntik</option><option>IUD</option><option>Implan</option><option>Alami</option></select></div>
  </div>
  <div><label class="text-sm font-medium">Riwayat hamil atau keguguran (opsional)</label><input name="pregnancy_history" value="{{ old('pregnancy_history', $profile->pregnancy_history ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div><label class="text-sm font-medium">Tujuan utama</label><select name="goal" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="kesehatan">Pantau kesehatan</option><option value="promil">Program hamil</option><option value="kb">Menunda kehamilan</option></select><p class="mt-1 text-xs text-stone-500">Bisa diganti kapan saja di Pengaturan. Mempengaruhi bahasa tips masa subur.</p></div>
  <button class="w-full rounded-full bg-rose-600 py-2.5 font-semibold text-white">Simpan dan lanjut</button>
</form>
@endsection
