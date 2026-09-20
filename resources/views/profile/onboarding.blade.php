@extends('layouts.app')
@section('title', 'Kenalan yuk')
@section('content')
<div x-data="{ step: 1 }" class="rounded-3xl bg-white p-5 shadow-sm">
  <div class="flex items-center gap-1.5">
    <template x-for="i in [1,2,3,4,5]"><span class="h-1.5 flex-1 rounded-full" :class="step >= i ? 'bg-rose-500' : 'bg-rose-100'"></span></template>
  </div>
  <form method="POST" action="{{ route('onboarding.store') }}" class="mt-4">
    @csrf

    <div x-show="step === 1">
      <h1 class="text-xl font-bold">Apa tujuanmu?</h1>
      <p class="mt-1 text-sm text-stone-500">Bisa diganti kapan saja di Menu.</p>
      <div class="mt-3 grid grid-cols-2 gap-2">
        <label><input type="radio" name="goal" value="kesehatan" class="peer hidden" checked><span class="block rounded-2xl border p-3 text-center text-sm peer-checked:border-rose-500 peer-checked:bg-rose-50"><strong>Pantau</strong><span class="block text-xs text-stone-500">Kesehatan siklus</span></span></label>
        <label><input type="radio" name="goal" value="promil" class="peer hidden"><span class="block rounded-2xl border p-3 text-center text-sm peer-checked:border-rose-500 peer-checked:bg-rose-50"><strong>Promil</strong><span class="block text-xs text-stone-500">Program hamil</span></span></label>
        <label><input type="radio" name="goal" value="kb" class="peer hidden"><span class="block rounded-2xl border p-3 text-center text-sm peer-checked:border-rose-500 peer-checked:bg-rose-50"><strong>KB</strong><span class="block text-xs text-stone-500">Menunda hamil</span></span></label>
        <label><input type="radio" name="goal" value="hamil" class="peer hidden"><span class="block rounded-2xl border p-3 text-center text-sm peer-checked:border-rose-500 peer-checked:bg-rose-50"><strong>Hamil</strong><span class="block text-xs text-stone-500">Sedang hamil</span></span></label>
      </div>
      <label class="mt-3 flex items-center gap-2 rounded-2xl bg-rose-50 p-3 text-sm"><input type="checkbox" name="is_teen" value="1">Ini haid pertamaku / aku remaja (bahasa lebih edukatif)</label>
    </div>

    <div x-show="step === 2" x-cloak>
      <h1 class="text-xl font-bold">Kapan haid terakhir mulai?</h1>
      <p class="mt-1 text-sm text-stone-500">Satu tanggal ini saja sudah cukup untuk prediksi pertama.</p>
      <input type="date" name="last_period" max="{{ date('Y-m-d') }}" class="mt-3 w-full rounded-xl border px-3 py-2.5">
    </div>

    <div x-show="step === 3" x-cloak>
      <h1 class="text-xl font-bold">Biasanya seperti apa?</h1>
      <p class="mt-1 text-sm text-stone-500">Boleh dikosongkan bila belum tahu. Bisa diubah nanti.</p>
      <div class="mt-3 grid grid-cols-2 gap-3">
        <div><label class="text-sm">Siklus biasa (hari)</label><input type="number" name="typical_cycle_length" min="21" max="45" placeholder="28" class="mt-1 w-full rounded-xl border px-3 py-2.5"></div>
        <div><label class="text-sm">Haid biasa (hari)</label><input type="number" name="typical_period_length" min="1" max="10" placeholder="5" class="mt-1 w-full rounded-xl border px-3 py-2.5"></div>
      </div>
    </div>

    <div x-show="step === 4" x-cloak>
      <h1 class="text-xl font-bold">Ceritakan sedikit tentangmu</h1>
      <div class="mt-3 space-y-3">
        <div><label class="text-sm">Nama lengkap</label><input name="full_name" required value="{{ old('full_name', $profile->full_name ?? '') }}" class="mt-1 w-full rounded-xl border px-3 py-2.5"></div>
        <div class="grid grid-cols-3 gap-3">
          <div><label class="text-sm">Lahir</label><input type="date" name="birth_date" class="mt-1 w-full rounded-xl border px-3 py-2.5"></div>
          <div><label class="text-sm">Tinggi cm</label><input type="number" name="height_cm" class="mt-1 w-full rounded-xl border px-3 py-2.5"></div>
          <div><label class="text-sm">Berat kg</label><input type="number" step="0.1" name="weight_kg" class="mt-1 w-full rounded-xl border px-3 py-2.5"></div>
        </div>
      </div>
    </div>

    <div x-show="step === 5" x-cloak>
      <h1 class="text-xl font-bold">Kesehatan dasar</h1>
      <p class="mt-1 text-sm text-stone-500">Membantu pembacaan pola. Opsional semua.</p>
      <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
        @foreach (['PCOS', 'Endometriosis', 'Tiroid', 'Anemia', 'Diabetes', 'Hipertensi'] as $c)
          <label class="flex items-center gap-2 rounded-xl border px-3 py-2"><input type="checkbox" name="conditions[]" value="{{ $c }}">{{ $c }}</label>
        @endforeach
      </div>
      <div class="mt-3 grid grid-cols-2 gap-3">
        <div><label class="text-sm">Obat rutin</label><input name="routine_meds" class="mt-1 w-full rounded-xl border px-3 py-2.5" placeholder="Opsional"></div>
        <div><label class="text-sm">Riwayat KB</label><select name="kb_history" class="mt-1 w-full rounded-xl border px-3 py-2.5"><option value="">Belum ada</option><option>Pil</option><option>Suntik</option><option>IUD</option><option>Implan</option><option>Alami</option></select></div>
      </div>
    </div>

    <div class="mt-5 flex gap-2">
      <button type="button" x-show="step > 1" @click="step--" class="rounded-full border px-5 py-2.5 text-sm">Kembali</button>
      <button type="button" x-show="step < 5" @click="step++" class="flex-1 rounded-full bg-rose-600 py-2.5 font-semibold text-white">Lanjut</button>
      <button type="submit" x-show="step === 5" class="flex-1 rounded-full bg-rose-600 py-2.5 font-semibold text-white">Selesai, lihat prediksiku</button>
    </div>
  </form>
</div>
@endsection
