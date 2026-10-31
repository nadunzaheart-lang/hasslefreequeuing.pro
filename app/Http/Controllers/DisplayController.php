<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class DisplayController extends Controller
{
    public function index()
    {
        return view('display.index');
    }

    // A fast endpoint for the TV to fetch data every second
    public function getData()
    {
        $servingQueues = Queue::where('status', 'serving')->get();
        $waiting = Queue::where('status', 'waiting')->orderBy('id', 'asc')->take(5)->get();

        $windows = [];
        foreach ($servingQueues as $q) {
            $windows[$q->window_number] = $q;
        }

        return response()->json([
            'windows' => $windows,
            'waiting' => $waiting,
        ]);
    }
}