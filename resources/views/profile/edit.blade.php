@extends('layouts.app')
@section('title', 'Edit profil')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Profil</h1>
<form method="POST" action="{{ route('profile.update') }}" class="mt-5 space-y-4 rounded-2xl bg-white p-6 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama lengkap</label><input name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div class="grid gap-4 sm:grid-cols-3">
    <div><label class="text-sm font-medium">Tanggal lahir</label><input type="date" name="birth_date" value="{{ old('birth_date', isset($profile->birth_date) ? $profile->birth_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm font-medium">Tinggi (cm)</label><input type="number" name="height_cm" value="{{ old('height_cm', $profile->height_cm ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm font-medium">Berat (kg)</label><input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $profile->weight_kg ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  </div>
  <div><label class="text-sm font-medium">Obat rutin</label><input name="routine_meds" value="{{ old('routine_meds', $profile->routine_meds ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div class="grid gap-4 sm:grid-cols-2">
    <div><label class="text-sm font-medium">Riwayat KB</label><input name="kb_history" value="{{ old('kb_history', $profile->kb_history ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm font-medium">Tujuan</label><select name="goal" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="kesehatan" {{ ($profile->goal ?? '') === 'kesehatan' ? 'selected' : '' }}>Pantau kesehatan</option><option value="promil" {{ ($profile->goal ?? '') === 'promil' ? 'selected' : '' }}>Program hamil</option><option value="kb" {{ ($profile->goal ?? '') === 'kb' ? 'selected' : '' }}>Menunda kehamilan</option></select></div>
  </div>
  <button class="w-full rounded-full bg-rose-600 py-2.5 font-semibold text-white">Simpan</button>
</form>
@endsection
