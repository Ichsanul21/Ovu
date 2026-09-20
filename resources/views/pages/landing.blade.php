@extends('layouts.app')
@section('title', 'Ovu, tracking haid dan kesuburan')
@section('content')
<div class="rounded-3xl bg-gradient-to-br from-rose-500 to-rose-700 p-8 text-white">
  <p class="text-sm uppercase tracking-wide text-rose-100">Gratis penuh, tanpa iklan</p>
  <h1 class="mt-2 text-3xl font-bold">Kenali siklusmu, rawat dirimu setiap hari.</h1>
  <p class="mt-3 max-w-xl text-rose-100">Ovu mencatat haid, masa subur, gejala harian, dan memberi tips lembut sesuai harimu. Cocok untuk program hamil, menunda kehamilan, atau sekadar memantau kesehatan.</p>
  <div class="mt-6 flex gap-3">
    <a href="{{ route('register') }}" class="rounded-full bg-white px-6 py-2.5 font-semibold text-rose-700">Mulai gratis</a>
    <a href="{{ route('login') }}" class="rounded-full border border-white/60 px-6 py-2.5">Masuk</a>
  </div>
</div>
<div class="mt-6 grid gap-4 sm:grid-cols-3">
  <div class="rounded-2xl bg-white p-5 shadow-sm"><h2 class="font-semibold text-rose-700">Kalender cerdas</h2><p class="mt-1 text-sm text-stone-600">Prediksi haid, ovulasi, dan masa subur dari catatanmu sendiri.</p></div>
  <div class="rounded-2xl bg-white p-5 shadow-sm"><h2 class="font-semibold text-rose-700">Catatan 30 detik</h2><p class="mt-1 text-sm text-stone-600">Gejala fisik, mood, energi, lendir serviks, dan diary harian.</p></div>
  <div class="rounded-2xl bg-white p-5 shadow-sm"><h2 class="font-semibold text-rose-700">Tips tiap hari</h2><p class="mt-1 text-sm text-stone-600">Kondisi umum dan saran lembut sesuai hari siklusmu.</p></div>
</div>
<p class="mt-6 text-center text-xs text-stone-500">Bisa dipasang ke layar HP seperti aplikasi. Datamu hanya milikmu.</p>
@endsection
