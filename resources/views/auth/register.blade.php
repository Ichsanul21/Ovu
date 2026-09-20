@extends('layouts.app')
@section('title', 'Daftar Ovu')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Buat akun Ovu</h1>
<p class="mt-1 text-sm text-stone-600">Gratis. Data milikmu, bukan untuk dijual.</p>
<form method="POST" action="{{ route('register') }}" class="mt-5 space-y-4 rounded-2xl bg-white p-6 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div class="grid gap-4 sm:grid-cols-2">
    <div><label class="text-sm font-medium">Password (min 8)</label><input type="password" name="password" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm font-medium">Ulangi password</label><input type="password" name="password_confirmation" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  </div>
  <div class="grid gap-4 sm:grid-cols-2">
    <div><label class="text-sm font-medium">Peran</label><select name="role" class="mt-1 w-full rounded-xl border px-3 py-2"><option value="wife">Istri (pemilik data)</option><option value="husband">Suami (butuh kode undangan)</option></select></div>
    <div><label class="text-sm font-medium">Kode undangan (khusus suami)</label><input name="partner_code" value="{{ old('partner_code') }}" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Contoh: A1B2C3"></div>
  </div>
  <label class="flex gap-2 text-sm"><input type="checkbox" name="consent_zero_use" value="1" class="mt-1">Saya telah membaca dan menyetujui <a href="{{ route('privacy') }}" class="text-rose-600 underline">Kebijakan Privasi</a> Ovu: data saya tidak digunakan untuk apa pun selain fungsi aplikasi, tidak dilatih ke AI, tidak dijual, tidak dibagikan ke pihak ketiga tanpa izin saya.</label>
  <label class="flex gap-2 text-sm"><input type="checkbox" name="consent_owner_only" value="1" class="mt-1">Saya paham hanya saya (pemilik akun) yang bisa mengakses data saya. Akun lain hanya bisa melihat jika saya undang dan bisa saya cabut kapan saja.</label>
  <button class="w-full rounded-full bg-rose-600 py-2.5 font-semibold text-white">Daftar</button>
  <p class="text-center text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="text-rose-600 underline">Masuk</a></p>
</form>
@endsection
