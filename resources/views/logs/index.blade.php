@extends('layouts.app')
@section('title', 'Catat hari ini')
@section('content')
<h1 class="text-xl font-bold">Catat {{ \Carbon\Carbon::parse($date)->translatedFormat('d M Y') }}</h1>
<form method="GET" action="{{ route('logs.index') }}" class="mt-2 flex gap-2">
  <input type="date" name="date" value="{{ $date }}" max="{{ date('Y-m-d') }}" class="flex-1 rounded-xl border bg-white px-3 py-2">
  <button class="rounded-full border bg-white px-4 text-sm">Lihat</button>
</form>

@if ($readonly)
  <div class="mt-3 rounded-2xl bg-white p-4 text-sm shadow-sm">
    @if ($log)<p>Darah level {{ $log->bleeding }}. Mood {{ $log->mood ?? '-' }}. Energi {{ $log->energy ?? '-' }}.</p>
    @else<p>Belum ada catatan hari ini.</p>@endif
  </div>
@else
<form method="POST" action="{{ route('logs.store') }}" class="mt-3 space-y-3 pb-4">
  @csrf
  <input type="hidden" name="log_date" value="{{ $date }}">
  @foreach ($catalog as $catKey => $cat)
  <details class="rounded-2xl bg-white shadow-sm" {{ $catKey === 'darah' ? 'open' : '' }}>
    <summary class="cursor-pointer p-4 text-sm font-semibold">{{ $cat['label'] }}</summary>
    <div class="space-y-3 px-4 pb-4">
      @foreach ($cat['items'] as $item)
        @php
          $isCol = str_starts_with($item['target'], 'col:');
          $field = ($item['input'] === 'check' ? 'sym_' : 'col_') . $item['key'];
          $val = $isCol ? ($saved['col_' . $item['key']] ?? null) : ($saved[$item['key']] ?? null);
          if ($item['input'] === 'check' && $isCol) $val = $saved[$item['key']] ?? false;
        @endphp
        @if ($item['input'] === 'check')
          <label class="flex items-center justify-between rounded-xl bg-rose-50 px-3 py-2 text-sm">
            <span>{{ $item['label'] }}</span>
            <input type="checkbox" name="{{ $field }}" value="1" {{ $val ? 'checked' : '' }} class="h-5 w-5 accent-rose-600">
          </label>
        @elseif ($item['input'] === 'level')
          <div>
            <p class="text-sm">{{ $item['label'] }}</p>
            <div class="mt-1 flex gap-1.5">
              @for ($i = 1; $i <= 5; $i++)
                <label class="flex-1"><input type="radio" name="{{ $field }}" value="{{ $i }}" class="peer hidden" {{ (int) $val === $i ? 'checked' : '' }}><span class="block rounded-xl border py-1.5 text-center text-sm peer-checked:border-rose-500 peer-checked:bg-rose-100 peer-checked:font-bold">{{ $i }}</span></label>
              @endfor
            </div>
          </div>
        @elseif ($item['input'] === 'select')
          <div>
            <p class="text-sm">{{ $item['label'] }}</p>
            <select name="{{ $field }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm">
              <option value="">Pilih</option>
              @foreach ($item['options'] as $ov => $ol)<option value="{{ $ov }}" {{ (string) $val === (string) $ov ? 'selected' : '' }}>{{ $ol }}</option>@endforeach
            </select>
          </div>
        @elseif ($item['input'] === 'number')
          <div>
            <p class="text-sm">{{ $item['label'] }}</p>
            <input type="number" step="0.1" name="{{ $field }}" value="{{ $val }}" class="mt-1 w-full rounded-xl border px-3 py-2 text-sm">
          </div>
        @endif
      @endforeach
    </div>
  </details>
  @endforeach
  <div class="rounded-2xl bg-white p-4 shadow-sm">
    <p class="text-sm font-semibold">Diary bebas</p>
    <textarea name="diary" rows="2" placeholder="Cerita harimu..." class="mt-1 w-full rounded-xl border px-3 py-2 text-sm">{{ old('diary', $log->diary ?? '') }}</textarea>
  </div>
  <button class="sticky bottom-24 w-full rounded-full bg-rose-600 py-3 font-semibold text-white shadow-lg">Simpan</button>
</form>
@endif
@endsection
