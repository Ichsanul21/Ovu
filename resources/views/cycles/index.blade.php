@extends('layouts.app')
@section('title', 'Riwayat haid')
@section('content')
<h1 class="text-xl font-bold">Riwayat haid</h1>
<p class="mt-1 text-xs text-stone-500">Rata-rata {{ $prediction['avg_cycle'] }} hari . {{ $prediction['count'] }} catatan.</p>
@unless ($readonly ?? false)
<form method="POST" action="{{ route('cycles.store') }}" class="mt-2 space-y-2 rounded-2xl bg-white p-4 shadow-sm">
  @csrf
  <p class="text-sm font-semibold">Tambah riwayat</p>
  <div class="grid grid-cols-2 gap-2">
    <div><label class="text-xs">Mulai</label><input type="date" name="start_date" required max="{{ date('Y-m-d') }}" class="mt-0.5 w-full rounded-xl border px-2 py-1.5 text-sm"></div>
    <div><label class="text-xs">Selesai</label><input type="date" name="end_date" class="mt-0.5 w-full rounded-xl border px-2 py-1.5 text-sm"></div>
  </div>
  <button class="w-full rounded-full bg-rose-600 py-2 text-sm font-semibold text-white">Simpan</button>
</form>
@endunless
<div class="mt-2 space-y-2">
  @forelse ($cycles as $c)
    <div class="flex items-center justify-between rounded-2xl bg-white p-3 text-sm shadow-sm">
      <div><p class="font-medium">{{ $c->start_date->translatedFormat('d M Y') }} @if($c->end_date) - {{ $c->end_date->translatedFormat('d M Y') }} @endif</p>
      @if ($c->cycle_length)<p class="text-xs text-stone-400">Jarak ke berikut: {{ $c->cycle_length }} hari</p>@endif</div>
      @unless ($readonly ?? false)
      <form method="POST" action="{{ route('cycles.destroy', $c) }}">@csrf @method('DELETE')<button class="text-xs text-red-500">Hapus</button></form>
      @endunless
    </div>
  @empty
    <p class="rounded-2xl bg-white p-4 text-sm text-stone-500 shadow-sm">Belum ada riwayat. Tambahkan minimal 1 tanggal haid.</p>
  @endforelse
</div>
@endsection
