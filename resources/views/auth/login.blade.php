@extends('layouts.app')
@section('title', 'Masuk Ovu')
@section('content')
<h1 class="text-xl font-bold">Masuk</h1>
<p class="mt-1 text-sm text-stone-500">Privat. Tanpa pelacak.</p>
<form method="POST" action="{{ route('login') }}" class="mt-3 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
  @csrf
  <div><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <div><label class="text-sm font-medium">Password</label><input type="password" name="password" required class="mt-1 w-full rounded-xl border px-3 py-2 text-sm"></div>
  <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="accent-rose-600">Ingat saya (matikan bila HP dipakai bersama)</label>
  <button class="w-full rounded-full bg-rose-600 py-2.5 text-sm font-semibold text-white">Masuk</button>
  <p class="text-center text-sm">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-rose-600">Daftar gratis</a></p>
</form>
@endsection
