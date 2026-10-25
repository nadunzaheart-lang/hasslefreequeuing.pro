<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Course;

class KioskController extends Controller
{
    public function createStudentInfo()
    {
        // Fetch ALL courses from the database
        $courses = Course::orderBy('course_name', 'asc')->get();
        return view('kiosk.student-info', compact('courses'));
    }

    public function storeStudentInfo(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'course'     => 'required|string|max:100',
            'year_level' => 'required|string|max:50',
        ]);

        $request->session()->put('student', $validated);
        return redirect()->route('kiosk.transaction');
    }

    public function storeTransaction(Request $request)
    {
        $student = $request->session()->get('student');
        if (!$student) return redirect()->route('kiosk.student-info');

        $validated = $request->validate([
            'transactions' => 'required|array|min:1',
        ]);

        $transactionList = implode(', ', $validated['transactions']);
        
        // Find the course to get the assigned window
        $courseRecord = Course::where('course_name', $student['course'])->first();
        $assignedWindow = $courseRecord ? $courseRecord->window_number : '01';

        $lastQueue = Queue::whereDate('created_at', today())->orderByDesc('id')->first();
        $nextNumber = $lastQueue ? ((int) substr($lastQueue->queue_number, 2)) + 1 : 101;
        $queueNumber = 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $queue = Queue::create([
            'queue_number' => $queueNumber,
            'student_name' => $student['first_name'] . ' ' . $student['last_name'],
            'student_id'   => $student['student_id'],
            'transaction'  => $transactionList,
            'status'       => 'waiting',
            'window_number'=> $assignedWindow,
        ]);

        $request->session()->put('queue_id', $queue->id);
        return redirect()->route('kiosk.queue-number');
    }

    public function showQueueNumber(Request $request)
    {
        $queueId = $request->session()->get('queue_id');
        if (!$queueId) return redirect()->route('kiosk.index');

        $queue = Queue::find($queueId);
        if (!$queue) return redirect()->route('kiosk.index');

        return view('kiosk.queue-number', compact('queue'));
    }
}