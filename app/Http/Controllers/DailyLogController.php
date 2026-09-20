<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyLogController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $ownerId = $user->visibleUserId();
        $date = $request->query('date', Carbon::today()->toDateString());

        $log = DailyLog::where('user_id', $ownerId)->where('log_date', $date)->first();
        $catalog = $this->catalog($user);
        $saved = $log ? $this->savedValues($log) : [];

        return view('logs.index', [
            'date' => $date,
            'log' => $log,
            'catalog' => $catalog,
            'saved' => $saved,
            'readonly' => ! $user->isWife(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $catalog = config('symptoms');
        $teen = (bool) ($user->profile->is_teen ?? false);

        $request->validate(['log_date' => ['required', 'date', 'before_or_equal:today']]);

        $columns = ['user_id' => $user->id, 'log_date' => $request->input('log_date')];
        $json = [];

        $existing = DailyLog::where('user_id', $user->id)
            ->where('log_date', $request->input('log_date'))->first();
        if ($existing && $existing->symptoms) {
            $json = $existing->symptomsList();
        }

        foreach ($catalog as $catKey => $cat) {
            if ($teen && ($cat['teen_safe'] ?? true) === false) {
                continue;
            }
            foreach ($cat['items'] as $item) {
                if ($teen && ! ($item['teen'] ?? true)) {
                    continue;
                }
                $key = $item['key'];
                $target = $item['target'];

                if ($item['input'] === 'check') {
                    $val = $request->boolean("sym_{$key}", false);
                    if (str_starts_with($target, 'col:')) {
                        $columns[substr($target, 4)] = $val;
                    } else {
                        $json[$key] = $val;
                    }
                } elseif ($item['input'] === 'level') {
                    $val = $request->input("col_{$key}");
                    if ($val !== null && $val !== '') {
                        $val = max(1, min(5, (int) $val));
                        if (str_starts_with($target, 'col:')) {
                            $columns[substr($target, 4)] = $val;
                        } else {
                            $json[$key] = $val;
                        }
                    }
                } elseif ($item['input'] === 'number') {
                    $val = $request->input("col_{$key}");
                    if ($val !== null && $val !== '') {
                        if (str_starts_with($target, 'col:')) {
                            $columns[substr($target, 4)] = (float) $val;
                        } else {
                            $json[$key] = $val;
                        }
                    }
                } elseif ($item['input'] === 'select') {
                    $val = $request->input('col_'.$key);
                    if ($val !== null && $val !== '') {
                        if (str_starts_with($target, 'col:')) {
                            $columns[substr($target, 4)] = $val;
                        } else {
                            $json[$key] = $val;
                        }
                    }
                }
            }
        }

        $columns['bleeding'] = $columns['bleeding'] ?? $existing->bleeding ?? 0;
        $columns['symptoms'] = empty($json) ? null : json_encode($json);
        $columns['diary'] = $request->input('diary');

        DailyLog::updateOrCreate(
            ['user_id' => $user->id, 'log_date' => $request->input('log_date')],
            $columns
        );

        if (! empty($columns['weight_kg']) && $user->profile) {
            $user->profile->update(['weight_kg' => $columns['weight_kg']]);
        }

        return redirect()->route('dashboard')->with('ok', 'Tersimpan. Tubuhmu berterima kasih sudah dicatat hari ini.');
    }

    public function togglePill(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $log = DailyLog::firstOrCreate(
            ['user_id' => $user->id, 'log_date' => $today],
            ['bleeding' => 0]
        );
        $log->update(['pill_taken' => ! $log->pill_taken]);

        return back()->with('ok', $log->pill_taken ? 'Pil hari ini ditandai sudah diminum.' : 'Tanda pil hari ini dibatalkan.');
    }

    /** @return array<string, mixed> */
    private function catalog($user): array
    {
        $all = config('symptoms');
        $teen = (bool) ($user->profile->is_teen ?? false);
        $visible = $user->profile->visible_categories
            ? json_decode($user->profile->visible_categories, true)
            : array_keys($all);

        $out = [];
        foreach ($all as $catKey => $cat) {
            if (! in_array($catKey, (array) $visible)) {
                continue;
            }
            if ($teen && ($cat['teen_safe'] ?? true) === false) {
                continue;
            }
            $items = array_values(array_filter(
                $cat['items'],
                fn ($i) => ! $teen || ($i['teen'] ?? true)
            ));
            if (! empty($items)) {
                $out[$catKey] = ['label' => $cat['label'], 'items' => $items];
            }
        }

        return $out;
    }

    /** @return array<string, mixed> */
    private function savedValues(DailyLog $log): array
    {
        $saved = $log->symptomsList();
        foreach ($log->getAttributes() as $col => $val) {
            if (! in_array($col, ['id', 'user_id', 'log_date', 'symptoms', 'diary', 'created_at', 'updated_at']) && $val !== null) {
                $saved['col_'.$col] = $val;
            }
        }
        foreach (['intercourse', 'protected', 'pill_taken'] as $boolKey) {
            $saved[$boolKey] = (bool) ($log->{$boolKey} ?? false);
        }

        return $saved;
    }
}
