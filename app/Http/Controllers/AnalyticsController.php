<?php

namespace App\Http\Controllers;

use App\Services\CyclePredictor;
use App\Services\InsightEngine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request, CyclePredictor $predictor, InsightEngine $insights): View
    {
        $ownerId = $request->user()->visibleUserId();
        $owner = \App\Models\User::findOrFail($ownerId);

        $cycles = $owner->cycles()->orderBy('start_date')->get()->values();
        $cycleLabels = [];
        $cycleValues = [];
        for ($i = 0; $i < $cycles->count() - 1; $i++) {
            $cycleLabels[] = $cycles[$i]->start_date->format('d M y');
            $cycleValues[] = (int) $cycles[$i]->start_date->diffInDays($cycles[$i + 1]->start_date);
        }

        $logs = $owner->dailyLogs()->orderBy('log_date', 'desc')->limit(90)->get()->sortBy('log_date')->values();

        $logLabels = [];
        $logEnergy = [];
        $logCramp = [];
        foreach ($logs as $log) {
            $logLabels[] = $log->log_date ? $log->log_date->format('d M') : '-';
            $logEnergy[] = $log->energy === null ? null : (int) $log->energy;
            $logCramp[] = $log->cramp === null ? null : (int) $log->cramp;
        }

        $prediction = $predictor->predict($owner);

        return view('analytics.index', [
            'cycleLabels' => $cycleLabels,
            'cycleValues' => $cycleValues,
            'logLabels' => $logLabels,
            'logEnergy' => $logEnergy,
            'logCramp' => $logCramp,
            'prediction' => $prediction,
            'insights' => $insights->build($owner),
        ]);
    }
}
