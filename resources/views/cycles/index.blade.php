@extends('layouts.app')
@section('title', 'Riwayat haid')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Riwayat haid</h1>
<p class="mt-1 text-sm text-stone-600">Rata-rata {{ $prediction['avg_cycle'] }} hari. Keyakinan {{ $prediction['confidence'] }} dari {{ $prediction['count'] }} catatan.</p>
@unless ($readonly ?? false)
<form method="POST" action="{{ route('cycles.store') }}" class="mt-4 space-y-3 rounded-2xl bg-white p-5 shadow-sm">
  @csrf
  <p class="font-medium">Tambah riwayat (tanggal mulai sampai selesai, dari ingatan juga boleh)</p>
  <div class="grid gap-3 sm:grid-cols-3">
    <div><label class="text-sm">Mulai</label><input type="date" name="start_date" required class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm">Selesai</label><input type="date" name="end_date" class="mt-1 w-full rounded-xl border px-3 py-2"></div>
    <div><label class="text-sm">Catatan</label><input name="notes" class="mt-1 w-full rounded-xl border px-3 py-2" placeholder="Opsional"></div>
  </div>
  <button class="rounded-full bg-rose-600 px-6 py-2 font-semibold text-white">Simpan</button>
</form>
@endunless
<div class="mt-4 space-y-2">
  @forelse ($cycles as $c)
    <div class="flex items-center justify-between rounded-2xl bg-white p-4 shadow-sm">
      <div><p class="font-medium">{{ $c->start_date->format('d M Y') }} @if($c->end_date) sampai {{ $c->end_date->format('d M Y') }} ({{ $c->bleedDays() }} hari) @endif</p>
      @if ($c->cycle_length)<p class="text-xs text-stone-500">Jarak ke haid berikut: {{ $c->cycle_length }} hari</p>@endif</div>
      @unless ($readonly ?? false)
      <form method="POST" action="{{ route('cycles.destroy', $c) }}">@csrf @method('DELETE')<button class="text-sm text-red-500">Hapus</button></form>
      @endunless
    </div>
  @empty
    <p class="rounded-2xl bg-white p-5 text-sm text-stone-600 shadow-sm">Belum ada riwayat. Tambahkan minimal 2 tanggal haid agar prediksi berjalan.</p>
  @endforelse
</div>
@endsection
