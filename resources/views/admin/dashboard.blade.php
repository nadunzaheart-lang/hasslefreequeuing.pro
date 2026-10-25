@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back, Administrator. Here is today\'s registrar queue overview.')

@section('content')

<!-- WELCOME HEADER -->
<div class="dashboard-header">
    <div>
        <h2>Registrar Overview</h2>
        <p>Monitor queue activity and all five service windows.</p>
    </div>
    <div class="header-actions">
        <button class="secondary-button"><span>↻</span> Refresh</button>
        <button class="primary-button"><span>+</span> New Queue</button>
    </div>
</div>


<!-- STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon blue">☷</div>
            <span class="stat-label">TODAY</span>
        </div>
        <div class="stat-value">{{ $todayTotal }}</div>
        <div class="stat-footer">
            <span class="stat-positive">Live Data</span>
            <span>queues today</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon orange">◷</div>
            <span class="stat-label">CURRENT</span>
        </div>
        <div class="stat-value">{{ $waitingCount }}</div>
        <div class="stat-footer">
            <span class="stat-warning">Waiting</span>
            <span>students in queue</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon purple">♙</div>
            <span class="stat-label">ACTIVE</span>
        </div>
        <div class="stat-value">{{ $servingCount }}</div>
        <div class="stat-footer">
            <span class="stat-active">{{ $servingCount }} / 5</span>
            <span>windows serving</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-icon green">✓</div>
            <span class="stat-label">COMPLETED</span>
        </div>
        <div class="stat-value">{{ $completedCount }}</div>
        <div class="stat-footer">
            <span class="stat-positive">{{ $completionRate }}%</span>
            <span>completion rate</span>
        </div>
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
            <!-- REAL SERVING DATA -->
            <div class="window-card serving">
                <div class="window-header">
                    <div>
                        <span class="window-number">WINDOW {{ $windowId }}</span>
                        <h3>Registrar {{ $i }}</h3>
                    </div>
                    <span class="window-status serving-status">Serving</span>
                </div>
                <div class="window-current">
                    <span>NOW SERVING</span>
                    <strong>{{ $queue->queue_number }}</strong>
                    <p>{{ $queue->student_name }}</p>
                    <small>{{ $queue->transaction }}</small>
                </div>
                <div class="window-footer">
                    <span>Next: <strong>—</strong></span>
                    <button class="small-button">View</button>
                </div>
            </div>
        @else
            <!-- AVAILABLE WINDOW -->
            <div class="window-card available">
                <div class="window-header">
                    <div>
                        <span class="window-number">WINDOW {{ $windowId }}</span>
                        <h3>Registrar {{ $i }}</h3>
                    </div>
                    <span class="window-status available-status">Available</span>
                </div>
                <div class="window-current available-window">
                    <span>READY FOR NEXT</span>
                    <strong>—</strong>
                    <p>No student currently serving</p>
                    <small>Waiting for next queue</small>
                </div>
                <div class="window-footer">
                    <span>Next: <strong>—</strong></span>
                    <button class="small-button">View</button>
                </div>
            </div>
        @endif
    @endfor
</div>


<!-- QUEUE SECTION -->
<div class="content-grid">
    <div class="queue-panel">
        <div class="panel-header">
            <div>
                <h2>Waiting Queue</h2>
                <p>Students currently waiting to be served.</p>
            </div>
            <a href="#" class="view-link">View All →</a>
        </div>

        <div class="table-wrapper">
            <table class="queue-table">
                <thead>
                    <tr>
                        <th>QUEUE NO.</th>
                        <th>STUDENT</th>
                        <th>TRANSACTION</th>
                        <th>WAIT TIME</th>
                        <th>STATUS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waitingList as $q)
                        <tr>
                            <td><span class="queue-number">{{ $q->queue_number }}</span></td>
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">{{ strtoupper(substr($q->student_name, 0, 2)) }}</div>
                                    <div>
                                        <strong>{{ $q->student_name }}</strong>
                                        <small>{{ $q->student_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="transaction">{{ $q->transaction }}</span></td>
                            <td>{{ $q->created_at->diffForHumans() }}</td>
                            <td><span class="status-badge waiting">Waiting</span></td>
                            <td><button class="table-action">•••</button></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No students waiting in the queue.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- QUICK PANEL -->
    <div class="quick-panel">
        <div class="panel-header">
            <div>
                <h2>Queue Summary</h2>
                <p>Today's queue activity.</p>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-icon blue">☷</div>
            <div class="summary-info">
                <strong>{{ $todayTotal }}</strong>
                <span>Total Queued</span>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-icon orange">◷</div>
            <div class="summary-info">
                <strong>{{ $waitingCount }}</strong>
                <span>Currently Waiting</span>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-icon purple">♙</div>
            <div class="summary-info">
                <strong>{{ $servingCount }}</strong>
                <span>Currently Serving</span>
            </div>
        </div>

        <div class="summary-item">
            <div class="summary-icon green">✓</div>
            <div class="summary-info">
                <strong>{{ $completedCount }}</strong>
                <span>Completed Today</span>
            </div>
        </div>

        <div class="announcement-card">
            <div class="announcement-icon">🔊</div>
            <div>
                <strong>Announcement System</strong>
                <span>Ready to announce queue numbers</span>
            </div>
            <span class="online-dot"></span>
        </div>
    </div>
</div>

@endsection