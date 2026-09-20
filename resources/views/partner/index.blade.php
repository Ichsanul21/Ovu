@extends('layouts.app')
@section('title', 'Akses pasangan')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Akses pasangan</h1>
<p class="mt-1 text-sm text-stone-600">Suami bisa melihat kalender dan status. Detail sensitif hanya bila kamu izinkan.</p>
<form method="POST" action="{{ route('partner.invite') }}" class="mt-4 rounded-2xl bg-white p-5 shadow-sm">@csrf<button class="rounded-full bg-rose-600 px-6 py-2 font-semibold text-white">Buat kode undangan (24 jam)</button></form>
<div class="mt-3 space-y-2">@foreach ($invites as $i)<div class="rounded-2xl bg-white p-3 text-sm shadow-sm">Kode <strong>{{ $i->code }}</strong>. Berlaku sampai {{ $i->expires_at->format('d M H:i') }}. {{ $i->used_at ? 'Sudah dipakai.' : 'Belum dipakai.' }}</div>@endforeach</div>
<form method="POST" action="{{ route('partner.share') }}" class="mt-4 space-y-2 rounded-2xl bg-white p-5 text-sm shadow-sm">
  @csrf
  <label class="flex items-center gap-2"><input type="checkbox" name="share_sensitive" value="1" {{ $shareSensitive ? 'checked' : '' }}>Izinkan pasangan melihat penyakit, obat, dan diary detail</label>
  <button class="rounded-full border px-5 py-1.5">Simpan</button>
</form>
<form method="POST" action="{{ route('partner.revoke') }}" class="mt-3 rounded-2xl bg-white p-5 text-sm shadow-sm" onsubmit="return confirm('Cabut akses pasangan?')">@csrf<button class="text-red-600">Cabut semua akses pasangan</button></form>
@endsection
