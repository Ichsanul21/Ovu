@extends('layouts.app')
@section('title', 'Menu Ovu')
@section('content')
<h1 class="text-xl font-bold">Menu</h1>
<form method="POST" action="{{ route('settings.update') }}" class="mt-2 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama tampilan</label><input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-sm font-medium">Tujuan</label><select name="goal" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"><option value="kesehatan" {{ ($user->profile->goal ?? '') === 'kesehatan' ? 'selected' : '' }}>Pantau</option><option value="promil" {{ ($user->profile->goal ?? '') === 'promil' ? 'selected' : '' }}>Promil</option><option value="kb" {{ ($user->profile->goal ?? '') === 'kb' ? 'selected' : '' }}>KB</option><option value="hamil" {{ ($user->profile->goal ?? '') === 'hamil' ? 'selected' : '' }}>Hamil</option></select></div>
    <div><label class="text-sm font-medium">Strip Beranda</label><select name="strip_days" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"><option value="7" {{ ($user->profile->strip_days ?? 7) == 7 ? 'selected' : '' }}>7 hari</option><option value="14" {{ ($user->profile->strip_days ?? 7) == 14 ? 'selected' : '' }}>14 hari</option></select></div>
  </div>
  <div><p class="text-sm font-medium">Kategori gejala yang tampil</p>
    <div class="mt-1 grid grid-cols-2 gap-1.5 text-sm">
      @php $vis = $user->profile->visible_categories ? json_decode($user->profile->visible_categories, true) : array_keys(config('symptoms')); @endphp
      @foreach (config('symptoms') as $ck => $cat)
        <label class="flex items-center gap-2 rounded-xl border px-2 py-1.5"><input type="checkbox" name="visible_categories[]" value="{{ $ck }}" {{ in_array($ck, (array) $vis) ? 'checked' : '' }} class="accent-rose-600">{{ $cat['label'] }}</label>
      @endforeach
    </div>
  </div>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-sm font-medium">Jam pil KB</label><input type="time" name="kb_pill_time" value="{{ $user->profile->kb_pill_time ?? '21:00' }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="kb_pill_active" value="1" {{ $user->profile->kb_pill_active ?? false ? 'checked' : '' }} class="accent-rose-600">Aktifkan pengingat pil</label>
  </div>
  @if ($user->isWife())
  <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="share_sensitive_with_partner" value="1" {{ $user->share_sensitive_with_partner ? 'checked' : '' }} class="accent-rose-600">Pasangan boleh lihat data sensitif</label>
  @endif
  <div><label class="text-sm font-medium">Password baru (opsional)</label><input type="password" name="password" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"><input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="mt-2 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <button class="w-full rounded-full bg-rose-600 py-2.5 text-sm font-semibold text-white">Simpan</button>
</form>

<form method="POST" action="{{ route('settings.pin') }}" class="mt-2 space-y-2 rounded-2xl bg-white p-4 shadow-sm">
  @csrf
  <p class="text-sm font-semibold">Kunci PIN (4 digit)</p>
  <div class="flex gap-2">
    <input type="password" name="pin" inputmode="numeric" maxlength="4" placeholder="{{ $user->pin_code ? 'Aktif. Isi untuk ganti, kosongkan untuk matikan.' : 'Belum aktif' }}" class="flex-1 rounded-xl border px-3 py-2 text-sm">
    <input type="password" name="current_password" required placeholder="Password" class="flex-1 rounded-xl border px-3 py-2 text-sm">
  </div>
  <button class="w-full rounded-full border py-2 text-sm">Simpan PIN</button>
</form>
@if ($user->pin_code)
<form method="POST" action="{{ route('settings.lock') }}">@csrf<button class="mt-2 w-full rounded-2xl bg-white p-3 text-sm shadow-sm">Kunci sekarang</button></form>
@endif

<div class="mt-2 grid grid-cols-2 gap-2">
  <a href="{{ route('settings.export') }}" class="rounded-2xl bg-white p-3 text-center text-sm font-medium shadow-sm">Export data</a>
  <a href="{{ route('push.settings') }}" class="rounded-2xl bg-white p-3 text-center text-sm font-medium shadow-sm">Notifikasi</a>
  @if (auth()->user()->isWife())
  <a href="{{ route('partner') }}" class="rounded-2xl bg-white p-3 text-center text-sm font-medium shadow-sm">Pasangan</a>
  <a href="{{ route('profile.edit') }}" class="rounded-2xl bg-white p-3 text-center text-sm font-medium shadow-sm">Profil</a>
  @endif
  <a href="{{ route('projection') }}" class="rounded-2xl bg-white p-3 text-center text-sm font-medium shadow-sm">Proyeksi 5 thn</a>
  <a href="{{ route('report') }}" class="rounded-2xl bg-white p-3 text-center text-sm font-medium shadow-sm">Laporan dokter</a>
</div>
<form method="POST" action="{{ route('settings.destroy') }}" class="mt-2 rounded-2xl bg-white p-4 text-sm shadow-sm" onsubmit="return confirm('Hapus akun dan SEMUA data?')">@csrf<label class="flex items-center gap-2"><input type="checkbox" name="confirm" value="1" required>Saya yakin hapus akun dan seluruh data</label><button class="mt-2 text-red-600">Hapus akun total</button></form>
@endsection
