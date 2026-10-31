<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Course;

class KioskController extends Controller
{
    public function createStudentInfo()
    {
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
        
        $courseRecord = Course::where('course_name', $student['course'])->first();
        $assignedWindow = $courseRecord ? $courseRecord->window_number : '01';

        // DETERMINE THE LETTER BASED ON WINDOW (1=A, 2=B, 3=C, 4=D, 5=E)
        $letters = [
            '01' => 'A',
            '02' => 'B',
            '03' => 'C',
            '04' => 'D',
            '05' => 'E'
        ];
        $prefix = $letters[$assignedWindow] ?? 'A';

        // Count how many queues exist for THIS specific window today to generate the number
        $lastQueue = Queue::whereDate('created_at', today())
            ->where('window_number', $assignedWindow)
            ->orderByDesc('id')
            ->first();

        if ($lastQueue) {
            // If the last ticket was A-005, extract the 5 and add 1
            $lastNumber = (int) substr($lastQueue->queue_number, 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // This creates A-001, B-001, etc.
        $queueNumber = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

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