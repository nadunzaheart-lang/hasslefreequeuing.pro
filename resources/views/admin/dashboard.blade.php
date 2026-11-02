@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back, Administrator. Here is today\'s registrar queue overview.')

@section('content')

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <!-- WELCOME HEADER -->
    <div class="dashboard-header">
        <div>
            <h2>Registrar Overview</h2>
            <p>Monitor queue activity and all five service windows.</p>
        </div>
        <div class="header-actions">
            <button class="secondary-button" onclick="window.location.reload();">
                <span>↻</span> Refresh
            </button>
            <!-- THIS OPENS THE MODAL -->
            <button class="primary-button" onclick="openModal()">
                <span>+</span> New Queue
            </button>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon blue">☷</div><span class="stat-label">TODAY</span></div>
            <div class="stat-value">{{ $todayTotal }}</div>
            <div class="stat-footer"><span class="stat-positive">Live Data</span><span>queues generated</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon orange">◷</div><span class="stat-label">CURRENT</span></div>
            <div class="stat-value">{{ $waitingCount }}</div>
            <div class="stat-footer"><span class="stat-warning">Waiting</span><span>students in queue</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon purple">♙</div><span class="stat-label">ACTIVE</span></div>
            <div class="stat-value">{{ $servingCount }}</div>
            <div class="stat-footer"><span class="stat-active">{{ $servingCount }} / 5</span><span>windows serving</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon green">✓</div><span class="stat-label">COMPLETED</span></div>
            <div class="stat-value">{{ $completedCount }}</div>
            <div class="stat-footer"><span class="stat-positive">{{ $completionRate }}%</span><span>completion rate</span></div>
        </div>
    </div>

    <!-- WINDOW SECTION -->
    <div class="section-header">
        <div>
            <h2>Registrar Windows</h2>
            <p>Current activity across all service windows.</p>
        </div>
        <a href="{{ route('admin.accounts') }}" class="view-link">Manage Windows →</a>
    </div>

    <div class="windows-grid">
        @for ($i = 1; $i <= 5; $i++)
            @php
                $windowId = str_pad($i, 2, '0', STR_PAD_LEFT);
                $isServing = isset($windows[$windowId]);
                $queue = $isServing ? $windows[$windowId] : null;
            @endphp

            @if($isServing)
                <div class="window-card serving">
                    <div class="window-header">
                        <div><span class="window-number">WINDOW {{ $windowId }}</span><h3>Registrar {{ $i }}</h3></div>
                        <span class="window-status serving-status">Serving</span>
                    </div>
                    <div class="window-current">
                        <span>NOW SERVING</span>
                        <strong>{{ $queue->queue_number }}</strong>
                        <p>{{ $queue->student_name }}</p>
                        <small>{{ $queue->transaction }}</small>
                    </div>
                    <div class="window-footer"><span>Next: <strong>—</strong></span><button class="small-button">View</button></div>
                </div>
            @else
                <div class="window-card available">
                    <div class="window-header">
                        <div><span class="window-number">WINDOW {{ $windowId }}</span><h3>Registrar {{ $i }}</h3></div>
                        <span class="window-status available-status">Available</span>
                    </div>
                    <div class="window-current available-window">
                        <span>READY FOR NEXT</span>
                        <strong>—</strong>
                        <p>No student currently serving</p>
                        <small>Waiting for next queue</small>
                    </div>
                    <div class="window-footer"><span>Next: <strong>—</strong></span><button class="small-button">View</button></div>
                </div>
            @endif
        @endfor
    </div>

    <!-- QUEUE SECTION -->
    <div class="content-grid">
        <div class="queue-panel">
            <div class="panel-header">
                <div><h2>Waiting Queue</h2><p>Students currently waiting to be served.</p></div>
                <a href="#" class="view-link">View All →</a>
            </div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead>
                        <tr><th>QUEUE NO.</th><th>STUDENT</th><th>TRANSACTION</th><th>WAIT TIME</th><th>STATUS</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($waitingList as $q)
                            <tr>
                                <td><span class="queue-number">{{ $q->queue_number }}</span></td>
                                <td>
                                    <div class="student-cell">
                                        <div class="student-avatar">{{ strtoupper(substr($q->student_name, 0, 2)) }}</div>
                                        <div><strong>{{ $q->student_name }}</strong><small>{{ $q->student_id }}</small></div>
                                    </div>
                                </td>
                                <td><span class="transaction">{{ $q->transaction }}</span></td>
                                <td>{{ $q->created_at->diffForHumans() }}</td>
                                <td><span class="status-badge waiting">Waiting</span></td>
                                <td><button class="table-action">•••</button></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align: center; padding: 20px;">No students waiting.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- QUICK PANEL -->
        <div class="quick-panel">
            <div class="panel-header"><div><h2>Queue Summary</h2><p>Today's queue activity.</p></div></div>
            <div class="summary-item">
                <div class="summary-icon blue">☷</div>
                <div class="summary-info"><strong>{{ $todayTotal }}</strong><span>Total Queued</span></div>
            </div>
            <div class="summary-item">
                <div class="summary-icon orange">◷</div>
                <div class="summary-info"><strong>{{ $waitingCount }}</strong><span>Currently Waiting</span></div>
            </div>
            <div class="summary-item">
                <div class="summary-icon purple">♙</div>
                <div class="summary-info"><strong>{{ $servingCount }}</strong><span>Currently Serving</span></div>
            </div>
            <div class="summary-item">
                <div class="summary-icon green">✓</div>
                <div class="summary-info"><strong>{{ $completedCount }}</strong><span>Completed Today</span></div>
            </div>
            <div class="announcement-card">
                <div class="announcement-icon">🔊</div>
                <div><strong>Announcement System</strong><span>Ready to announce queue numbers</span></div>
                <span class="online-dot"></span>
            </div>
        </div>
    </div>

    <!-- MANUAL QUEUE MODAL -->
    <div id="manualQueueModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-top: 0; color: var(--color-primary);">Generate Manual Queue</h3>
            <p style="font-size: 13px; color: var(--color-muted); margin-bottom: 20px;">Use this if the Kiosk is offline or for VIP walk-ins.</p>

            <form action="{{ route('admin.manual-queue') }}" method="POST">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Student Name</label>
                    <input type="text" name="student_name" required placeholder="e.g. Juan Dela Cruz" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 6px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Assign to Window</label>
                    <select name="window_number" required style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 6px;">
                        @foreach($allWindows as $win)
                            <option value="{{ $win }}">Window {{ $win }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="closeModal()" style="flex: 1; padding: 12px; background: var(--color-bg); border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Cancel</button>
                    <button type="submit" style="flex: 1; padding: 12px; background: var(--color-primary); color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Generate</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('manualQueueModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('manualQueueModal').style.display = 'none';
        }
    </script>
@endsection
