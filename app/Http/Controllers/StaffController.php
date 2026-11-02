<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class StaffController extends Controller
{
    public function dashboard()
    {
        return view('staff.dashboard');
    }

    public function getData()
    {
        $windowNumber = auth()->user()->window_number ?? '01';

        $serving = Queue::where('status', 'serving')
            ->where('window_number', $windowNumber)
            ->first();

        $waiting = Queue::where('status', 'waiting')
            ->where('window_number', $windowNumber)
            ->orderBy('id', 'asc')
            ->take(4)
            ->get();

        $waitingCount = Queue::where('status', 'waiting')
            ->where('window_number', $windowNumber)
            ->count();

        return response()->json([
            'serving' => $serving,
            'waiting' => $waiting,
            'waitingCount' => $waitingCount,
            'windowNumber' => $windowNumber,
            'timestamp' => now()->timestamp
        ]);
    }

    public function callNext(Request $request)
    {
        $windowNumber = auth()->user()->window_number ?? '01';

        $current = Queue::where('status', 'serving')->where('window_number', $windowNumber)->first();
        if ($current) {
            $current->update(['status' => 'completed']);
        }

        $next = Queue::where('status', 'waiting')->where('window_number', $windowNumber)->orderBy('id', 'asc')->first();
        if ($next) {
            $next->update(['status' => 'serving']);
        }

        return redirect()->route('staff.dashboard');
    }

    public function complete(Request $request)
    {
        $windowNumber = auth()->user()->window_number ?? '01';
        $current = Queue::where('status', 'serving')->where('window_number', $windowNumber)->first();
        if ($current) {
            $current->update(['status' => 'completed']);
        }
        return redirect()->route('staff.dashboard');
    }

    public function skip(Request $request)
    {
        $windowNumber = auth()->user()->window_number ?? '01';
        $current = Queue::where('status', 'serving')->where('window_number', $windowNumber)->first();
        if ($current) {
            $current->update(['status' => 'skipped']);
        }
        return redirect()->route('staff.dashboard');
    }
}