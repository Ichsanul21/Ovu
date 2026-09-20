<?php

namespace App\Http\Controllers;

use App\Services\CyclePredictor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(Request $request, CyclePredictor $predictor): View
    {
        $ownerId = $request->user()->visibleUserId();
        $owner = \App\Models\User::findOrFail($ownerId);
        $months = (int) $request->query('months', 3);
        $months = in_array($months, [1, 3, 6, 12]) ? $months : 3;

        $since = now()->subMonths($months)->startOfDay();
        $cycles = $owner->cycles()->where('start_date', '>=', $since)->orderBy('start_date')->get();
        $logs = $owner->dailyLogs()->where('log_date', '>=', $since)->orderBy('log_date')->get();
        $prediction = $predictor->predict($owner);

        return view('report.index', compact('owner', 'cycles', 'logs', 'prediction', 'months'));
    }

    public function pdf(Request $request, CyclePredictor $predictor): Response
    {
        $ownerId = $request->user()->visibleUserId();
        $owner = \App\Models\User::findOrFail($ownerId);
        $months = (int) $request->query('months', 3);
        $months = in_array($months, [1, 3, 6, 12]) ? $months : 3;

        $since = now()->subMonths($months)->startOfDay();
        $cycles = $owner->cycles()->where('start_date', '>=', $since)->orderBy('start_date')->get();
        $logs = $owner->dailyLogs()->where('log_date', '>=', $since)->orderBy('log_date')->get();
        $prediction = $predictor->predict($owner);

        $pdf = Pdf::loadView('report.pdf', compact('owner', 'cycles', 'logs', 'prediction', 'months'));

        return $pdf->download('ovu-laporan-'.$months.'-bulan.pdf');
    }
}
