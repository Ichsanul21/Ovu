@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')
<h1 class="text-2xl font-bold text-rose-700">Notifikasi push</h1>
<p class="mt-1 text-sm text-stone-600">Aktifkan agar dapat pengingat haid, masa subur, keterlambatan, dan berat badan. Isi push selalu umum, tanpa detail sensitif.</p>
<div class="mt-4 rounded-2xl bg-white p-5 shadow-sm">
  <button id="btn-push" class="rounded-full bg-rose-600 px-6 py-2 font-semibold text-white">Aktifkan notifikasi</button>
  <p id="push-status" class="mt-2 text-sm text-stone-600"></p>
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
      if (!key) { status.textContent = 'Push belum dikonfigurasi di server (VAPID kosong).'; return; }
      const sub = await reg.pushManager.subscribe({userVisibleOnly: true, applicationServerKey: key});
      await fetch('{{ route('push.subscribe') }}', {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'}, body: JSON.stringify(sub.toJSON())});
      status.textContent = 'Notifikasi aktif. Kamu akan mendapat pengingat jam 7 pagi.';
    } catch (e) { status.textContent = 'Gagal mengaktifkan: ' + e.message; }
  });
</script>
@endpush
@endsection
