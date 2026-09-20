@extends('layouts.app')
@section('title', 'Pengaturan')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Pengaturan</h1>
<form method="POST" action="{{ route('settings.update') }}" class="mt-4 space-y-3 rounded-2xl bg-white p-5 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama tampilan</label><input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div class="grid gap-3 sm:grid-cols-2">
    <div><label class="text-sm font-medium">Tujuan</label><select name="goal" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="kesehatan" {{ ($user->profile->goal ?? '') === 'kesehatan' ? 'selected' : '' }}>Pantau kesehatan</option><option value="promil" {{ ($user->profile->goal ?? '') === 'promil' ? 'selected' : '' }}>Program hamil</option><option value="kb" {{ ($user->profile->goal ?? '') === 'kb' ? 'selected' : '' }}>Menunda kehamilan</option></select></div>
    <div><label class="text-sm font-medium">Password baru (opsional)</label><input type="password" name="password" class="mt-1 w-full rounded-xl border px-3 py-2"><input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="mt-2 w-full rounded-xl border px-3 py-2"></div>
  </div>
  @if ($user->isWife())
  <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="share_sensitive_with_partner" value="1" {{ $user->share_sensitive_with_partner ? 'checked' : '' }}>Pasangan boleh lihat data sensitif</label>
  @endif
  <button class="w-full rounded-full bg-rose-600 py-2 font-semibold text-white">Simpan</button>
</form>
<div class="mt-4 grid gap-3 sm:grid-cols-2">
  <a href="{{ route('settings.export') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Export data (JSON)</a>
  <a href="{{ route('push.settings') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Notifikasi push</a>
  @if (auth()->user()->isWife())
  <a href="{{ route('partner') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Akses pasangan</a>
  <a href="{{ route('profile.edit') }}" class="rounded-2xl bg-white p-4 text-center text-sm font-medium shadow-sm">Edit profil</a>
  @endif
</div>
<form method="POST" action="{{ route('settings.destroy') }}" class="mt-4 rounded-2xl bg-white p-5 text-sm shadow-sm" onsubmit="return confirm('Hapus akun dan SEMUA data? Tindakan ini tidak bisa dibatalkan.')">@csrf<label class="flex items-center gap-2"><input type="checkbox" name="confirm" value="1" required>Saya yakin ingin menghapus akun dan seluruh data saya</label><button class="mt-2 text-red-600">Hapus akun total</button></form>
@endsection
