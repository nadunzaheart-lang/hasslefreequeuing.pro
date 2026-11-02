@extends('layouts.app')

@section('title', 'Queue Monitor')
@section('page-title', 'Live Queue Monitor')
@section('page-description', 'Real-time view of all waiting and serving students.')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
        <div class="queue-panel">
            <div class="panel-header"><div><h2>Currently Serving</h2><p>Students at the windows right now.</p></div></div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead><tr><th>QUEUE</th><th>STUDENT</th><th>WINDOW</th></tr></thead>
                    <tbody>
                        @forelse($serving as $s)
                            <tr>
                                <td><span class="queue-number">{{ $s->queue_number }}</span></td>
                                <td>{{ $s->student_name }}</td>
                                <td><span class="status-badge" style="background: #dcfce7; color: #166534;">Window {{ $s->window_number }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align: center; padding: 20px;">No students currently serving.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="queue-panel">
            <div class="panel-header"><div><h2>Waiting Queue</h2><p>Students waiting to be called.</p></div></div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead><tr><th>QUEUE</th><th>STUDENT</th><th>WINDOW</th></tr></thead>
                    <tbody>
                        @forelse($waiting as $w)
                            <tr>
                                <td><span class="queue-number">{{ $w->queue_number }}</span></td>
                                <td>{{ $w->student_name }}</td>
                                <td><span class="status-badge" style="background: #f3f4f6; color: #374151;">Window {{ $w->window_number }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align: center; padding: 20px;">No students waiting.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection