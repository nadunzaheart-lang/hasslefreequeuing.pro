@extends('layouts.app')

@section('title', 'Reports')
@section('page-title', 'Daily Reports')
@section('page-description', 'Summary of queue activity.')

@section('content')
    <!-- DATE FILTER -->
    <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div>
            <h3 style="margin: 0; font-size: 18px; color: var(--color-primary);">Report Date</h3>
            <p style="color: var(--color-muted); margin: 5px 0 0 0; font-size: 13px;">Select a date to view the queue activity for that day.</p>
        </div>
        <form method="GET" action="{{ route('admin.reports') }}" style="display: flex; gap: 10px;">
            <input type="date" name="date" value="{{ $date }}" style="padding: 10px; border: 1px solid var(--color-border); border-radius: 6px; font-family: inherit;">
            <button type="submit" class="primary-button" style="padding: 10px 20px;">View Report</button>
        </form>
    </div>

    <!-- STAT CARDS -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon blue">☷</div><span class="stat-label">TOTAL TICKETS</span></div>
            <div class="stat-value">{{ $totalToday }}</div>
            <div class="stat-footer"><span class="stat-positive">Generated</span><span>on this date</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon green">✓</div><span class="stat-label">COMPLETED</span></div>
            <div class="stat-value">{{ $completedToday }}</div>
            <div class="stat-footer"><span class="stat-positive">{{ $completionRate }}%</span><span>successfully served</span></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><div class="stat-icon orange">→</div><span class="stat-label">SKIPPED</span></div>
            <div class="stat-value">{{ $skippedToday }}</div>
            <div class="stat-footer"><span class="stat-warning">Missed</span><span>or skipped</span></div>
        </div>
    </div>

    <!-- BREAKDOWN TABLES -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">

        <!-- WINDOW BREAKDOWN -->
        <div class="queue-panel">
            <div class="panel-header">
                <div><h2>Traffic by Window</h2><p>Number of tickets generated per window.</p></div>
            </div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead>
                        <tr><th>WINDOW</th><th>TOTAL TICKETS</th></tr>
                    </thead>
                    <tbody>
                        @forelse($windowBreakdown as $w)
                            <tr>
                                <td>
                                    <span class="status-badge" style="background: #f6e9ea; color: var(--color-primary);">
                                        Window {{ $w->window_number }}
                                    </span>
                                </td>
                                <td><strong>{{ $w->total }}</strong> students</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; padding: 20px;">No tickets generated for this date.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TRANSACTION BREAKDOWN -->
        <div class="queue-panel">
            <div class="panel-header">
                <div><h2>Traffic by Transaction</h2><p>The most requested services.</p></div>
            </div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead>
                        <tr><th>TRANSACTION TYPE</th><th>TOTAL</th></tr>
                    </thead>
                    <tbody>
                        @forelse($transactionBreakdown as $t)
                            <tr>
                                <td><span class="transaction">{{ $t->transaction }}</span></td>
                                <td><strong>{{ $t->total }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; padding: 20px;">No transactions for this date.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
