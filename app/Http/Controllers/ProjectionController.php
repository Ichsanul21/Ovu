<?php

namespace App\Http\Controllers;

use App\Services\CyclePredictor;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectionController extends Controller
{
    public function index(Request $request, CyclePredictor $predictor): View
    {
        $ownerId = $request->user()->visibleUserId();
        $owner = \App\Models\User::findOrFail($ownerId);

        $projection = $predictor->project($owner, 5);
        $prediction = $predictor->predict($owner);

        $byYear = [];
        foreach ($projection as $p) {
            $byYear[$p['start']->year][] = $p;
        }

        return view('projection.index', [
            'byYear' => $byYear,
            'prediction' => $prediction,
            'count' => $prediction['count'],
        ]);
    }
}
