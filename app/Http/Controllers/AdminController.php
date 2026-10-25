<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\User;
use App\Models\Course;

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
        return view('admin.dashboard', compact('todayTotal', 'waitingCount', 'servingCount', 'completedCount', 'completionRate', 'waitingList', 'windows'));
    }

    public function history()
    {
        $history = Queue::whereIn('status', ['completed', 'skipped'])->orderBy('updated_at', 'desc')->paginate(20);
        return view('admin.history', compact('history'));
    }

    public function accounts()
    {
        $staff = User::where('is_admin', false)->orderBy('id', 'desc')->get();
        return view('admin.accounts', compact('staff'));
    }

    public function windows()
    {
        $courses = Course::orderBy('window_number', 'asc')->get()->groupBy('window_number');
        return view('admin.windows', compact('courses'));
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'window_number' => 'required|string',
        ], [
            'email.unique' => 'This email is already registered. Please use a different email address.'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'window_number' => $request->window_number,
            'is_admin' => false,
        ]);

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