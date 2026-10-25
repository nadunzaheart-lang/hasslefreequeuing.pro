<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Window - Staff</title>
    <link rel="stylesheet" href="{{ asset('css/staff.css') }}">
</head>
<body>

<div class="staff-app">
    <aside class="staff-sidebar">
        <div class="staff-brand">
            <img src="{{ asset('images/logo.png') }}" alt="TCC Logo" width="50" height="50">
            <div>
                <h1>Registrar</h1>
                <span>Queuing System</span>
            </div>
        </div>

        <div class="window-profile">
            <span class="profile-label">ASSIGNED WINDOW</span>
            <div class="window-profile-number">WINDOW {{ $windowNumber }}</div>
            <div class="staff-online"><span></span> Online</div>
        </div>

        <nav class="staff-navigation">
            <span class="navigation-label">MENU</span>
            <a href="{{ route('staff.dashboard') }}" class="staff-nav-item active">
                <span class="nav-symbol">▦</span> Dashboard
            </a>
            <a href="#queue" class="staff-nav-item">
                <span class="nav-symbol">☷</span> My Queue
            </a>
            <a href="#history" class="staff-nav-item">
                <span class="nav-symbol">◷</span> My History
            </a>
        </nav>

        <div class="staff-sidebar-bottom">
            <div class="staff-user">
                <div class="staff-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div class="staff-user-info">
                    <strong>{{ Auth::user()->name }}</strong>
                    <span>Window Staff</span>
                </div>
            </div>

            <a href="{{ route('logout') }}" class="logout-button" style="width: 100%; display: block; text-decoration: none; box-sizing: border-box;">
                <span>↪</span> Sign Out
            </a>
        </div>
    </aside>

    <main class="staff-main">
        <header class="staff-topbar">
            <div>
                <span class="topbar-label">REGISTRAR WINDOW</span>
                <h2>Window {{ $windowNumber }}</h2>
            </div>
            <div class="topbar-right">
                <div class="topbar-clock">
                    <strong id="staffTime">--:--</strong>
                    <span id="staffDate">Loading...</span>
                </div>
                <div class="staff-top-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            </div>
        </header>

        <section class="staff-content">
            <div class="staff-page-heading">
                <div>
                    <h2>Good day, {{ Auth::user()->name }}</h2>
                    <p>Manage the students assigned to Window {{ $windowNumber }}.</p>
                </div>
                <div class="window-status-large"><span></span> Window Online</div>
            </div>

            <div class="current-section">
                <div class="section-title">
                    <div>
                        <span>CURRENTLY SERVING</span>
                        <h3>Student Transaction</h3>
                    </div>
                    <span class="serving-badge">SERVING</span>
                </div>

                @if($serving)
                    <div class="current-card">
                        <div class="current-queue">
                            <span>QUEUE NUMBER</span>
                            <strong>{{ $serving->queue_number }}</strong>
                        </div>
                        <div class="current-student">
                            <div class="large-student-avatar">{{ strtoupper(substr($serving->student_name, 0, 2)) }}</div>
                            <div>
                                <span>STUDENT</span>
                                <h3>{{ $serving->student_name }}</h3>
                                <p>Student ID: <strong>{{ $serving->student_id }}</strong></p>
                            </div>
                        </div>
                        <div class="current-transaction">
                            <span>TRANSACTION</span>
                            <strong>{{ $serving->transaction }}</strong>
                        </div>
                        <div class="service-time">
                            <span>STARTED</span>
                            <strong>{{ $serving->updated_at->format('h:i A') }}</strong>
                        </div>
                    </div>
                @else
                    <div class="current-card" style="justify-content: center; padding: 40px; text-align: center; color: #666;">
                        <h3>No student currently serving.</h3>
                        <p>Click "Call Next Student" below to start.</p>
                    </div>
                @endif

                <div class="service-actions">
                    <form action="{{ route('staff.skip') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="service-button skip" {{ !$serving ? 'disabled' : '' }}>
                            <span>→</span> SKIP
                        </button>
                    </form>

                    <form action="{{ route('staff.complete') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="service-button complete" {{ !$serving ? 'disabled' : '' }}>
                            <span>✓</span> COMPLETE TRANSACTION
                        </button>
                    </form>
                </div>
            </div>

            <div class="queue-section" id="queue">
                <div class="section-title">
                    <div>
                        <span>UP NEXT</span>
                        <h3>Waiting Queue</h3>
                    </div>
                    <span class="waiting-count">{{ $waitingCount }} waiting</span>
                </div>

                <div class="queue-list">
                    @forelse($waiting as $w)
                        <div class="next-queue-item">
                            <div class="next-number">{{ $w->queue_number }}</div>
                            <div class="next-student">
                                <strong>{{ $w->student_name }}</strong>
                                <span>{{ $w->student_id }}</span>
                            </div>
                            <div class="next-service">{{ $w->transaction }}</div>
                            <div class="next-wait">{{ $w->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p style="padding: 20px; color: #666;">No students waiting.</p>
                    @endforelse
                </div>

                <form action="{{ route('staff.call-next') }}" method="POST">
                    @csrf
                    <button type="submit" class="call-next-button" style="width: 100%;">
                        <span class="call-icon">→</span>
                        <span style="text-align: left;">
                            <strong>CALL NEXT STUDENT</strong>
                            <small>
                                @if($waiting->count() > 0)
                                    {{ $waiting->first()->queue_number }} will be called to Window {{ $windowNumber }}
                                @else
                                    No students waiting
                                @endif
                            </small>
                        </span>
                    </button>
                </form>
            </div>

            <div class="history-section" id="history">
                <div class="section-title">
                    <div>
                        <span>TODAY</span>
                        <h3>My Activity</h3>
                    </div>
                </div>
                <div class="activity-grid">
                    <div class="activity-card">
                        <div class="activity-icon completed">✓</div>
                        <div><strong>18</strong><span>Completed</span></div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon skipped">→</div>
                        <div><strong>2</strong><span>Skipped</span></div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon time">◷</div>
                        <div><strong>6 min</strong><span>Avg. Service Time</span></div>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon total">☷</div>
                        <div><strong>20</strong><span>Total Served</span></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script src="{{ asset('js/staff.js') }}"></script>
</body>
</html>