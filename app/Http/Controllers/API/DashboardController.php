<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\Poli;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats()
    {
        $today = Carbon::today()->toDateString();

        $stats = [
            'total_patients' => Patient::count(),
            'today_queues' => Queue::where('queue_date', $today)->count(),
            'completed_today' => Queue::where('queue_date', $today)->where('status', 'selesai')->count(),
            'waiting_today' => Queue::where('queue_date', $today)->where('status', 'menunggu')->count(),
            'total_poli' => Poli::count()
        ];

        return response()->json($stats);
    }

    public function getTodayQueues()
    {
        $today = Carbon::today()->toDateString();

        $queues = Queue::with(['patient', 'poli'])
            ->where('queue_date', $today)
            ->orderBy('queue_number')
            ->get();

        return response()->json($queues);
    }
}
