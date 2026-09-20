@extends('layouts.app')
@section('title', 'Edit profil')
@section('content')
<h1 class="text-xl font-bold">Profil</h1>
<form method="POST" action="{{ route('profile.update') }}" class="mt-2 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama lengkap</label><input name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <div class="grid grid-cols-3 gap-2">
    <div><label class="text-sm">Lahir</label><input type="date" name="birth_date" value="{{ old('birth_date', isset($profile->birth_date) ? $profile->birth_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
    <div><label class="text-sm">Tinggi cm</label><input type="number" name="height_cm" value="{{ old('height_cm', $profile->height_cm ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
    <div><label class="text-sm">Berat kg</label><input type="number" step="0.1" name="weight_kg" value="{{ old('weight_kg', $profile->weight_kg ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  </div>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-sm">Siklus biasa (hari)</label><input type="number" name="typical_cycle_length" min="21" max="45" value="{{ old('typical_cycle_length', $profile->typical_cycle_length ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
    <div><label class="text-sm">Haid biasa (hari)</label><input type="number" name="typical_period_length" min="1" max="10" value="{{ old('typical_period_length', $profile->typical_period_length ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  </div>
  <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_teen" value="1" {{ old('is_teen', $profile->is_teen ?? false) ? 'checked' : '' }} class="accent-rose-600">Mode remaja edukatif</label>
  <div><label class="text-sm">Obat rutin</label><input name="routine_meds" value="{{ old('routine_meds', $profile->routine_meds ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-sm">Riwayat KB</label><input name="kb_history" value="{{ old('kb_history', $profile->kb_history ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
    <div><label class="text-sm">Tujuan</label><select name="goal" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"><option value="kesehatan" {{ ($profile->goal ?? '') === 'kesehatan' ? 'selected' : '' }}>Pantau</option><option value="promil" {{ ($profile->goal ?? '') === 'promil' ? 'selected' : '' }}>Promil</option><option value="kb" {{ ($profile->goal ?? '') === 'kb' ? 'selected' : '' }}>KB</option><option value="hamil" {{ ($profile->goal ?? '') === 'hamil' ? 'selected' : '' }}>Hamil</option></select></div>
  </div>
  <button class="w-full rounded-full bg-rose-600 py-2.5 text-sm font-semibold text-white">Simpan</button>
</form>
@endsection
