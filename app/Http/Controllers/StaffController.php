<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class StaffController extends Controller
{
    public function dashboard()
    {
        // THIS IS THE MAGIC LINE: Get the window directly from the logged-in user
        $windowNumber = auth()->user()->window_number;

        // If they somehow don't have a window, kick them out
        if (!$windowNumber) {
            return redirect('/login')->withErrors(['email' => 'No window assigned to this account. Contact Admin.']);
        }

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

        return view('staff.dashboard', compact('serving', 'waiting', 'waitingCount', 'windowNumber'));
    }

    public function callNext()
    {
        $windowNumber = auth()->user()->window_number;

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

    public function complete()
    {
        $windowNumber = auth()->user()->window_number;
        $current = Queue::where('status', 'serving')->where('window_number', $windowNumber)->first();
        if ($current) {
            $current->update(['status' => 'completed']);
        }
        return redirect()->route('staff.dashboard');
    }

    public function skip()
    {
        $windowNumber = auth()->user()->window_number;
        $current = Queue::where('status', 'serving')->where('window_number', $windowNumber)->first();
        if ($current) {
            $current->update(['status' => 'skipped']);
        }
        return redirect()->route('staff.dashboard');
    }
}