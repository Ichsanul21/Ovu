<?php

namespace App\Http\Controllers;

use App\Services\BmiService;
use App\Services\CyclePredictor;
use App\Services\CycleTipsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        CyclePredictor $predictor,
        CycleTipsService $tips,
        BmiService $bmi
    ): View {
        $user = $request->user();
        $ownerId = $user->visibleUserId();
        $owner = $ownerId === $user->id ? $user : \App\Models\User::findOrFail($ownerId);

        $prediction = $predictor->predict($owner);
        $phase = $predictor->phase($prediction['cycle_day'], $prediction['avg_cycle']);

        $goal = $owner->profile->goal ?? 'kesehatan';
        $tip = $prediction['cycle_day']
            ? $tips->forDay($prediction['cycle_day'], $goal)
            : null;

        $bmiValue = $bmi->bmi(
            $owner->profile->weight_kg ?? null,
            $owner->profile->height_cm ?? null
        );

        $today = Carbon::today()->toDateString();
        $todayLog = \App\Models\DailyLog::where('user_id', $ownerId)
            ->where('log_date', $today)->first();

        return view('dashboard', [
            'owner' => $owner,
            'prediction' => $prediction,
            'phase' => $phase,
            'tip' => $tip,
            'goal' => $goal,
            'bmi' => $bmiValue,
            'bmiCategory' => $bmi->category($bmiValue),
            'todayLog' => $todayLog,
            'readonly' => ! $user->isWife(),
        ]);
    }
}
