@extends('layouts.app')
@section('title', 'Masuk Ovu')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Masuk</h1>
<p class="mt-1 flex items-center gap-1 text-sm text-stone-600">Privat. Data hanya di server Ovu. Tanpa pelacak.</p>
<form method="POST" action="{{ route('login') }}" class="mt-5 space-y-4 rounded-2xl bg-white p-6 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <div><label class="text-sm font-medium">Password</label><input type="password" name="password" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
  <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1">Ingat saya (matikan bila HP dipakai bersama)</label>
  <button class="w-full rounded-full bg-rose-600 py-2.5 font-semibold text-white">Masuk</button>
  <p class="text-center text-sm">Belum punya akun? <a href="{{ route('register') }}" class="text-rose-600 underline">Daftar gratis</a></p>
</form>
@endsection
