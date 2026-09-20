@extends('layouts.app')
@section('title', 'Daftar Ovu')
@section('content')
<h1 class="text-xl font-bold">Buat akun Ovu</h1>
<p class="mt-1 text-sm text-stone-500">Gratis selamanya. Tanpa paywall.</p>
<form method="POST" action="{{ route('register') }}" class="mt-3 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Nama</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <div><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-sm font-medium">Password</label><input type="password" name="password" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
    <div><label class="text-sm font-medium">Ulangi</label><input type="password" name="password_confirmation" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  </div>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-sm font-medium">Peran</label><select name="role" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"><option value="wife">Istri</option><option value="husband">Suami</option></select></div>
    <div><label class="text-sm font-medium">Kode undangan</label><input name="partner_code" value="{{ old('partner_code') }}" placeholder="Khusus suami" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  </div>
  <label class="flex gap-2 text-xs text-stone-600"><input type="checkbox" name="consent_zero_use" value="1" class="mt-0.5 accent-rose-600"><span>Saya setuju <a href="{{ route('privacy') }}" class="underline">Kebijakan Privasi</a>: data saya tidak digunakan selain fungsi aplikasi, tidak dilatih ke AI, tidak dijual, tidak dibagikan tanpa izin saya.</span></label>
  <label class="flex gap-2 text-xs text-stone-600"><input type="checkbox" name="consent_owner_only" value="1" class="mt-0.5 accent-rose-600"><span>Saya paham hanya saya yang bisa mengakses data saya. Akun lain hanya bila saya undang dan bisa saya cabut.</span></label>
  <button class="w-full rounded-full bg-rose-600 py-2.5 text-sm font-semibold text-white">Daftar</button>
  <p class="text-center text-sm">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-rose-600">Masuk</a></p>
</form>
@endsection
