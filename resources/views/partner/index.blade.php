@extends('layouts.app')
@section('title', 'Ovu untuk Pasangan')
@section('content')
<h1 class="text-xl font-bold">Ovu untuk Pasangan</h1>
<p class="mt-1 text-sm text-stone-500">Bagi beban, bukan cuma kabar. Pasanganmu bisa melihat kalender dan status siklus agar tahu kapan mendukungmu. Kamu yang pegang kendali penuh atas apa yang dibagikan.</p>
<div class="mt-2 space-y-2 text-sm">
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold">1. Buat kode undangan</p><p class="text-stone-500">Berlaku 24 jam. Berikan ke pasanganmu.</p></div>
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold">2. Pasangan daftar dengan kode itu</p><p class="text-stone-500">Akun pasangan hanya bisa melihat, tidak bisa mengubah.</p></div>
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold">3. Atur batas berbagi</p><p class="text-stone-500">Detail sensitif (penyakit, obat, diary) default disembunyikan. Bisa dicabut kapan saja.</p></div>
</div>
<form method="POST" action="{{ route('partner.invite') }}" class="mt-2 rounded-2xl bg-white p-4 shadow-sm">@csrf<button class="w-full rounded-full bg-rose-600 py-2.5 text-sm font-semibold text-white">Buat kode undangan</button></form>
<div class="mt-2 space-y-2">@foreach ($invites as $i)<div class="rounded-2xl bg-white p-3 text-sm shadow-sm">Kode <strong>{{ $i->code }}</strong> . sampai {{ $i->expires_at->translatedFormat('d M H:i') }} . {{ $i->used_at ? 'Sudah dipakai.' : 'Belum dipakai.' }}</div>@endforeach</div>
<form method="POST" action="{{ route('partner.share') }}" class="mt-2 space-y-2 rounded-2xl bg-white p-4 text-sm shadow-sm">
  @csrf
  <label class="flex items-center gap-2"><input type="checkbox" name="share_sensitive" value="1" {{ $shareSensitive ? 'checked' : '' }} class="accent-rose-600">Izinkan pasangan melihat data sensitif</label>
  <button class="rounded-full border px-5 py-1.5 text-sm">Simpan</button>
</form>
<form method="POST" action="{{ route('partner.revoke') }}" class="mt-2 rounded-2xl bg-white p-4 text-sm shadow-sm" onsubmit="return confirm('Cabut akses pasangan?')">@csrf<button class="text-red-600">Cabut semua akses pasangan</button></form>
@endsection
