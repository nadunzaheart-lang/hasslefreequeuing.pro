<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class DisplayController extends Controller
{
    public function index()
    {
        $servingQueues = Queue::where('status', 'serving')->get();

        // Get the next 5 students waiting and ONLY select the queue_number
        $waiting = Queue::where('status', 'waiting')
            ->orderBy('id', 'asc')
            ->take(5)
            ->get();

        $windows = [];
        foreach ($servingQueues as $q) {
            $windows[$q->window_number] = $q;
        }

        return view('display.index', compact('windows', 'waiting'));
    }
}