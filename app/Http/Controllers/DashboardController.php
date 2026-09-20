<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use App\Models\User;
use App\Services\AssistantService;
use App\Services\BmiService;
use App\Services\CyclePredictor;
use App\Services\CycleTipsService;
use App\Services\PregnancyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        CyclePredictor $predictor,
        CycleTipsService $tips,
        BmiService $bmi,
        AssistantService $assistant,
        PregnancyService $pregnancy
    ): View {
        $user = $request->user();
        $ownerId = $user->visibleUserId();
        $owner = $ownerId === $user->id ? $user : User::findOrFail($ownerId);
        $today = Carbon::today();

        $goal = $owner->profile->goal ?? 'kesehatan';
        $teen = (bool) ($owner->profile->is_teen ?? false);

        if ($goal === 'hamil') {
            return view('pregnancy.home', [
                'owner' => $owner,
                'status' => $pregnancy->status($owner),
                'readonly' => ! $user->isWife(),
            ]);
        }

        $prediction = $predictor->predict($owner, $today);
        $phase = $predictor->phase($prediction['cycle_day'], $prediction['avg_cycle']);
        $countdown = $predictor->countdown($today, $prediction['ovulation_date'], $prediction['next_period']);
        $chance = $predictor->pregnancyChance($today, $prediction['ovulation_date'], $prediction['fertile_start'], $prediction['fertile_end']);

        $tip = $prediction['cycle_day']
            ? $tips->forDay($prediction['cycle_day'], $goal, $teen)
            : null;

        $todayLog = DailyLog::where('user_id', $ownerId)->where('log_date', $today->toDateString())->first();

        $stripDays = (int) ($owner->profile->strip_days ?? 7);
        $stripDays = $stripDays === 14 ? 14 : 7;
        $strip = [];
        $half = intdiv($stripDays, 2);
        for ($i = -$half; $i < $stripDays - $half; $i++) {
            $d = $today->copy()->addDays($i);
            $strip[] = ['date' => $d, 'is_today' => $d->isToday()];
        }

        $bmiValue = $bmi->bmi($owner->profile->weight_kg ?? null, $owner->profile->height_cm ?? null);

        $pill = null;
        if ($goal === 'kb' && ($owner->profile->kb_pill_active ?? false)) {
            $pill = [
                'time' => $owner->profile->kb_pill_time ?? '21:00',
                'taken' => (bool) ($todayLog->pill_taken ?? false),
            ];
        }

        return view('home.index', [
            'owner' => $owner,
            'prediction' => $prediction,
            'phase' => $phase,
            'countdown' => $countdown,
            'chance' => $chance,
            'tip' => $tip,
            'goal' => $goal,
            'teen' => $teen,
            'strip' => $strip,
            'stripDays' => $stripDays,
            'bmi' => $bmiValue,
            'bmiCategory' => $bmi->category($bmiValue),
            'todayLog' => $todayLog,
            'cards' => $assistant->cards($owner, $prediction, $todayLog),
            'cycleStats' => $this->cycleStats($owner),
            'pill' => $pill,
            'readonly' => ! $user->isWife(),
        ]);
    }

    /** @return array{count: int, avg: float|null, normal: bool|null, regular: bool|null} */
    private function cycleStats(User $owner): array
    {
        $cycles = $owner->cycles()->orderBy('start_date')->get()->values();
        $lengths = collect();
        for ($i = 0; $i < $cycles->count() - 1; $i++) {
            $lengths->push($cycles[$i]->start_date->diffInDays($cycles[$i + 1]->start_date));
        }

        if ($lengths->isEmpty()) {
            return ['count' => $cycles->count(), 'avg' => null, 'normal' => null, 'regular' => null];
        }

        $avg = round($lengths->avg(), 1);

        return [
            'count' => $cycles->count(),
            'avg' => $avg,
            'normal' => $avg >= 21 && $avg <= 35,
            'regular' => $lengths->count() < 2 ? null : ($lengths->max() - $lengths->min() <= 7),
        ];
    }
}
