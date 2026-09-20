@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<h1 class="text-xl font-bold">Notifikasi</h1>
<p class="mt-1 text-sm text-stone-500">Pengingat haid, masa subur, keterlambatan, berat badan, dan pil KB. Isi push selalu umum, tanpa detail sensitif.</p>
<div class="mt-2 rounded-2xl bg-white p-4 shadow-sm">
  <button id="btn-push" class="w-full rounded-full bg-rose-600 py-2.5 text-sm font-semibold text-white">Aktifkan notifikasi</button>
  <p id="push-status" class="mt-2 text-sm text-stone-500"></p>
</div>
@push('scripts')
<script>
  document.getElementById('btn-push').addEventListener('click', async () => {
    const status = document.getElementById('push-status');
    try {
      const perm = await Notification.requestPermission();
      if (perm !== 'granted') { status.textContent = 'Izin notifikasi ditolak.'; return; }
      const reg = await navigator.serviceWorker.ready;
      const res = await fetch('{{ route('push.vapid') }}');
      const {key} = await res.json();
      if (!key) { status.textContent = 'Push belum dikonfigurasi di server.'; return; }
      const sub = await reg.pushManager.subscribe({userVisibleOnly: true, applicationServerKey: key});
      await fetch('{{ route('push.subscribe') }}', {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify(sub.toJSON())});
      status.textContent = 'Notifikasi aktif.';
    } catch (e) { status.textContent = 'Gagal: ' + e.message; }
  });
</script>
@endpush
@endsection
