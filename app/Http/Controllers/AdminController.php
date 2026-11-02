<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\User;
use App\Models\Course;
use App\Models\Setting;
use App\Models\Window;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todayTotal = Queue::whereDate('created_at', today())->count();
        $waitingCount = Queue::where('status', 'waiting')->count();
        $servingCount = Queue::where('status', 'serving')->count();
        $completedCount = Queue::where('status', 'completed')->whereDate('updated_at', today())->count();
        $completionRate = $todayTotal > 0 ? round(($completedCount / $todayTotal) * 100, 1) : 0;
        $waitingList = Queue::where('status', 'waiting')->orderBy('id', 'asc')->take(5)->get();
        
        $servingQueues = Queue::where('status', 'serving')->get();
        $windows = [];
        foreach ($servingQueues as $q) {
            $windows[$q->window_number] = $q;
        }

        $allWindows = Window::orderBy('window_number', 'asc')->get();

        return view('admin.dashboard', compact('todayTotal', 'waitingCount', 'servingCount', 'completedCount', 'completionRate', 'waitingList', 'windows', 'allWindows'));
    }

    public function manualQueue(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'window_number' => 'required|string',
        ]);

        $windowNum = $request->window_number;
        $windowRecord = Window::where('window_number', $windowNum)->first();
        $prefix = $windowRecord ? $windowRecord->letter_prefix : 'A';

        $lastQueue = Queue::whereDate('created_at', today())
            ->where('window_number', $windowNum)
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastQueue ? ((int) substr($lastQueue->queue_number, 2)) + 1 : 1;
        $queueNumber = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Queue::create([
            'queue_number' => $queueNumber,
            'student_name' => $request->student_name,
            'student_id' => 'WALK-IN',
            'transaction' => 'Manual Walk-in',
            'status' => 'waiting',
            'window_number' => $windowNum,
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Manual ticket {$queueNumber} created for Window {$windowNum}!");
    }

    public function queue()
    {
        $waiting = Queue::where('status', 'waiting')->orderBy('id', 'asc')->get();
        $serving = Queue::where('status', 'serving')->get();
        return view('admin.queue', compact('waiting', 'serving'));
    }

    public function history()
    {
        $history = Queue::whereIn('status', ['completed', 'skipped'])->orderBy('updated_at', 'desc')->paginate(20);
        return view('admin.history', compact('history'));
    }

    public function reports(Request $request)
    {
        $date = $request->input('date', today()->toDateString());

        $totalToday = Queue::whereDate('created_at', $date)->count();
        $completedToday = Queue::where('status', 'completed')->whereDate('updated_at', $date)->count();
        $skippedToday = Queue::where('status', 'skipped')->whereDate('updated_at', $date)->count();
        $completionRate = $totalToday > 0 ? round(($completedToday / $totalToday) * 100, 1) : 0;

        $windowBreakdown = Queue::whereDate('created_at', $date)
            ->select('window_number', DB::raw('count(*) as total'))
            ->groupBy('window_number')
            ->orderBy('window_number')
            ->get();

        $transactionBreakdown = Queue::whereDate('created_at', $date)
            ->select('transaction', DB::raw('count(*) as total'))
            ->groupBy('transaction')
            ->orderByDesc('total')
            ->get();

        return view('admin.reports', compact(
            'totalToday', 'completedToday', 'skippedToday', 
            'completionRate', 'windowBreakdown', 'transactionBreakdown', 'date'
        ));
    }

    public function settings()
    {
        $settings = [
            'college_name' => Setting::where('key', 'college_name')->value('value') ?? 'Tagoloan Community College',
            'office_name' => Setting::where('key', 'office_name')->value('value') ?? 'School Registrar',
            'voice_enabled' => Setting::where('key', 'voice_enabled')->value('value') ?? '1',
            'daily_reset_time' => Setting::where('key', 'daily_reset_time')->value('value') ?? '00:00',
            'ticket_digits' => Setting::where('key', 'ticket_digits')->value('value') ?? '3',
            'voice_repeat' => Setting::where('key', 'voice_repeat')->value('value') ?? '3',
            'voice_pause' => Setting::where('key', 'voice_pause')->value('value') ?? '1500',
            'voice_speed' => Setting::where('key', 'voice_speed')->value('value') ?? '0.85',
            'display_next_count' => Setting::where('key', 'display_next_count')->value('value') ?? '5',
            'display_refresh_speed' => Setting::where('key', 'display_refresh_speed')->value('value') ?? '1000',
        ];
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'college_name' => 'required|string|max:255',
            'office_name' => 'required|string|max:255',
        ]);

        $keys = ['college_name', 'office_name', 'daily_reset_time', 'ticket_digits', 'voice_repeat', 'voice_pause', 'voice_speed', 'display_next_count', 'display_refresh_speed'];
        
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => $request->input($key)]);
            }
        }

        $voiceStatus = $request->has('voice_enabled') ? '1' : '0';
        Setting::updateOrCreate(['key' => 'voice_enabled'], ['value' => $voiceStatus]);

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }

    public function accounts()
    {
        $staff = User::where('is_admin', false)->orderBy('id', 'desc')->get();
        $windows = Window::orderBy('window_number', 'asc')->get();
        return view('admin.accounts', compact('staff', 'windows'));
    }

    public function windows()
    {
        $windows = Window::orderBy('window_number', 'asc')->get();
        $courses = Course::orderBy('window_number', 'asc')->get()->groupBy('window_number');
        return view('admin.windows', compact('courses', 'windows'));
    }

    public function storeWindow(Request $request)
    {
        $request->validate([
            'window_number' => 'required|string|unique:windows',
            'name' => 'required|string',
            'letter_prefix' => 'required|string|max:1',
        ]);

        Window::create([
            'window_number' => $request->window_number,
            'name' => $request->name,
            'letter_prefix' => strtoupper($request->letter_prefix),
        ]);

        return redirect()->route('admin.windows')->with('success', 'New window created successfully!');
    }

    public function deleteWindow($id)
    {
        Window::findOrFail($id)->delete();
        return redirect()->route('admin.windows')->with('success', 'Window deleted.');
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'window_number' => 'required|string|exists:windows,window_number',
        ], [
            'email.unique' => 'This email is already registered. Please use a different email address.',
            'window_number.exists' => 'Please choose a valid window. Add the window first on the Windows page.',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'window_number' => $request->window_number,
                'is_admin' => false,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['account' => 'Could not create the account: ' . $e->getMessage()]);
        }

        return redirect()->route('admin.accounts')->with('success', 'Staff account created successfully!');
    }

    public function deleteAccount($id)
    {
        $user = User::findOrFail($id);
        if ($user->is_admin) {
            return redirect()->route('admin.accounts')->with('error', 'Cannot delete Admin account.');
        }
        $user->delete();
        return redirect()->route('admin.accounts')->with('success', 'Staff account deleted successfully.');
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:50',
            'course_name' => 'required|string|max:255',
            'window_number' => 'required|string',
        ]);

        Course::create([
            'course_code' => $request->course_code,
            'course_name' => $request->course_name,
            'window_number' => $request->window_number,
        ]);

        return redirect()->route('admin.windows')->with('success', 'Course added!');
    }

    public function deleteCourse($id)
    {
        Course::findOrFail($id)->delete();
        return redirect()->route('admin.windows')->with('success', 'Course deleted.');
    }
}