@extends('layouts.app')
@section('title', 'Ovu, kenali siklusmu')
@section('content')
<div class="rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-6 text-white">
  <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-8 border-white/30">
    <span class="text-center text-xs font-semibold">Hari ke<br><span class="text-2xl">28</span></span>
  </div>
  <h1 class="mt-4 text-center text-2xl font-bold">Kenali siklusmu, rawat dirimu.</h1>
  <p class="mt-2 text-center text-sm text-rose-100">Prediksi haid dan masa subur, 70+ gejala, tips harian yang lembut. Gratis, tanpa paywall, tanpa iklan.</p>
  <div class="mt-4 flex gap-2">
    <a href="{{ route('register') }}" class="flex-1 rounded-full bg-white py-2.5 text-center font-semibold text-rose-700">Mulai gratis</a>
    <a href="{{ route('login') }}" class="flex-1 rounded-full border border-white/60 py-2.5 text-center text-white">Masuk</a>
  </div>
</div>
<div class="mt-3 grid grid-cols-2 gap-2 text-sm">
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold text-rose-700">Lingkaran siklus</p><p class="text-xs text-stone-500">Hitung mundur ovulasi dan haid tiap hari.</p></div>
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold text-rose-700">Catat 1 ketuk</p><p class="text-xs text-stone-500">Gejala lengkap dalam 30 detik.</p></div>
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold text-rose-700">Proyeksi 5 tahun</p><p class="text-xs text-stone-500">Selalu menyesuaikan data aslimu.</p></div>
  <div class="rounded-2xl bg-white p-4 shadow-sm"><p class="font-semibold text-rose-700">Laporan dokter</p><p class="text-xs text-stone-500">Rekap rapi siap cetak PDF.</p></div>
</div>
<p class="mt-3 text-center text-[11px] text-stone-400">Pasang ke layar HP seperti aplikasi. Data milikmu, bukan untuk dijual.</p>
@endsection
