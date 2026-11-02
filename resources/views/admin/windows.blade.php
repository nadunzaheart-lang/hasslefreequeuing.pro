@extends('layouts.app')

@section('title', 'Manage Windows')
@section('page-title', 'Windows & Course Routing')
@section('page-description', 'Add windows and assign courses to them.')

@section('content')

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <!-- TOP: FORMS -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
        
        <!-- ADD NEW WINDOW -->
        <div class="window-card" style="height: fit-content; border-top: 4px solid #1e3a8a;">
            <div class="window-header" style="padding-bottom: 10px; border-bottom: 1px solid #eee;">
                <div>
                    <h3 style="margin: 0; color: #1e3a8a;">Add New Window</h3>
                    <p style="font-size: 13px; color: #666; margin-top: 5px;">Expand your system by creating a new window.</p>
                </div>
            </div>
            <div style="padding: 20px;">
                <form action="{{ route('admin.windows.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Window Number</label>
                            <input type="text" name="window_number" required placeholder="e.g. 06" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Letter Prefix</label>
                            <input type="text" name="letter_prefix" required placeholder="e.g. F" maxlength="1" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Window Name</label>
                        <input type="text" name="name" required placeholder="e.g. Registrar 6" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <button type="submit" class="primary-button" style="width: 100%; justify-content: center; background: #1e3a8a;">+ Create Window</button>
                </form>
            </div>
        </div>

        <!-- ADD NEW COURSE -->
        <div class="window-card" style="height: fit-content; border-top: 4px solid #16a34a;">
            <div class="window-header" style="padding-bottom: 10px; border-bottom: 1px solid #eee;">
                <div>
                    <h3 style="margin: 0; color: #16a34a;">Add Course Routing</h3>
                    <p style="font-size: 13px; color: #666; margin-top: 5px;">Assign a course to a specific window.</p>
                </div>
            </div>
            <div style="padding: 20px;">
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Course Code</label>
                            <input type="text" name="course_code" required placeholder="e.g. BSIT" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Window</label>
                            <select name="window_number" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                                @foreach($windows as $win)
                                    <option value="{{ $win->window_number }}">{{ $win->name }} ({{ $win->window_number }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Course Name</label>
                        <input type="text" name="course_name" required placeholder="e.g. BS Information Technology" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <button type="submit" class="primary-button" style="width: 100%; justify-content: center; background: #16a34a;">+ Add Course</button>
                </form>
            </div>
        </div>
    </div>

    <!-- BOTTOM: LISTS -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
        
        <!-- WINDOWS LIST -->
        <div class="queue-panel">
            <div class="panel-header"><div><h2>Active Windows</h2><p>All operational registrar windows.</p></div></div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead><tr><th>WINDOW</th><th>PREFIX</th><th>NAME</th><th>ACTION</th></tr></thead>
                    <tbody>
                        @forelse($windows as $w)
                            <tr>
                                <td><strong>{{ $w->window_number }}</strong></td>
                                <td><span class="status-badge" style="background: #e0e7ff; color: #3730a3;">{{ $w->letter_prefix }}</span></td>
                                <td>{{ $w->name }}</td>
                                <td>
                                    <form action="{{ route('admin.windows.delete', $w->id) }}" method="POST" onsubmit="return confirm('Delete this window?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: bold;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; padding: 20px;">No windows created yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- YOUR COURSE ROUTING LIST (UNCHANGED) -->
        <div class="queue-panel">
            <div class="panel-header"><div><h2>Course Routing</h2><p>Courses assigned to each window.</p></div></div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead><tr><th>WINDOW</th><th>ASSIGNED COURSES</th></tr></thead>
                    <tbody>
                        @forelse($courses as $window => $windowCourses)
                            <tr>
                                <td><span class="status-badge" style="background: #e0e7ff; color: #3730a3; font-size: 14px; padding: 5px 10px;">Window {{ $window }}</span></td>
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