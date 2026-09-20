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
        $lengths = collect();
        for ($i = 0; $i < $cycles->count() - 1; $i++) {
            $lengths->push([
                'label' => $cycles[$i]->start_date->format('d M y'),
                'length' => $cycles[$i]->start_date->diffInDays($cycles[$i + 1]->start_date),
            ]);
        }

        $logs = $owner->dailyLogs()->orderBy('log_date', 'desc')->limit(90)->get()->reverse()->values();

        $prediction = $predictor->predict($owner);

        return view('analytics.index', [
            'lengths' => $lengths,
            'logs' => $logs,
            'prediction' => $prediction,
            'insights' => $insights->build($owner),
        ]);
    }
}
