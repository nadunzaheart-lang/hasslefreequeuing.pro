@extends('layouts.app')

@section('title', 'Manage Windows')
@section('page-title', 'Windows & Course Routing')
@section('page-description', 'Assign courses to specific registrar windows.')

@section('content')

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px;">
        <div class="window-card" style="height: fit-content;">
            <div class="window-header" style="padding-bottom: 10px; border-bottom: 1px solid #eee;">
                <div>
                    <h3 style="margin: 0;">Add Course Routing</h3>
                    <p style="font-size: 13px; color: #666; margin-top: 5px;">Assign a course to a window.</p>
                </div>
            </div>
            <div style="padding: 20px;">
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Code</label>
                            <input type="text" name="course_code" required placeholder="e.g. BSIT" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Window</label>
                            <select name="window_number" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                                <option value="01">Window 01</option>
                                <option value="02">Window 02</option>
                                <option value="03">Window 03</option>
                                <option value="04">Window 04</option>
                                <option value="05">Window 05</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Course Name</label>
                        <input type="text" name="course_name" required placeholder="e.g. BS Information Technology" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <button type="submit" class="primary-button" style="width: 100%; justify-content: center;">+ Add Course</button>
                </form>
            </div>
        </div>

        <div class="queue-panel">
            <div class="panel-header">
                <div><h2>Course Routing</h2><p>Courses assigned to each window.</p></div>
            </div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead><tr><th>WINDOW</th><th>ASSIGNED COURSES</th></tr></thead>
                    <tbody>
                        @forelse($courses as $window => $windowCourses)
                            <tr>
                                <td>
                                    <span class="status-badge" style="background: #e0e7ff; color: #3730a3; font-size: 14px; padding: 5px 10px;">Window {{ $window }}</span>
                                </td>
                                <td>
                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                        @foreach($windowCourses as $c)
                                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px dashed #eee;">
                                                <div><strong>{{ $c->course_code }}</strong> - {{ $c->course_name }}</div>
                                                <form action="{{ route('admin.courses.delete', $c->id) }}" method="POST" onsubmit="return confirm('Delete this course?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-size: 12px;">Delete</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; padding: 20px;">No courses added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection