@extends('layouts.app')
@section('title', 'PIN Ovu')
@section('content')
<div class="mx-auto mt-10 max-w-xs rounded-3xl bg-white p-6 text-center shadow-sm">
  <h1 class="text-xl font-bold">Ovu terkunci</h1>
  <p class="mt-1 text-sm text-stone-500">Masukkan PIN 4 digit.</p>
  <form method="POST" action="{{ route('pin.unlock') }}" class="mt-4">@csrf
    <input type="password" name="pin" inputmode="numeric" maxlength="4" required autofocus class="w-full rounded-xl border px-3 py-2.5 text-center text-2xl tracking-[0.5em]">
    <button class="mt-3 w-full rounded-full bg-rose-600 py-2.5 font-semibold text-white">Buka</button>
  </form>
  <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf<button class="text-xs text-stone-400 underline">Keluar akun</button></form>
</div>
@endsection
