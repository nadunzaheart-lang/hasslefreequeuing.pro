@extends('layouts.app')

@section('title', 'Queue History')
@section('page-title', 'Queue History')
@section('page-description', 'View all completed and skipped transactions.')

@section('content')
    <div class="queue-panel" style="margin-top: 20px;">
        <div class="table-wrapper">
            <table class="queue-table">
                <thead>
                    <tr>
                        <th>QUEUE NO.</th>
                        <th>STUDENT</th>
                        <th>TRANSACTION</th>
                        <th>WINDOW</th>
                        <th>DATE FINISHED</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $h)
                        <tr>
                            <td><span class="queue-number">{{ $h->queue_number }}</span></td>
                            <td>
                                <div class="student-cell">
                                    <div class="student-avatar">{{ strtoupper(substr($h->student_name, 0, 2)) }}</div>
                                    <div>
                                        <strong>{{ $h->student_name }}</strong>
                                        <small>{{ $h->student_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="transaction">{{ $h->transaction }}</span></td>
                            <td><span class="status-badge" style="background: #f3f4f6; color: #374151;">Window {{ $h->window_number }}</span></td>
                            <td>{{ $h->updated_at->format('M d, Y - h:i A') }}</td>
                            <td>
                                @if($h->status == 'completed')
                                    <span class="status-badge" style="background: #dcfce7; color: #166534;">Completed</span>
                                @else
                                    <span class="status-badge" style="background: #fee2e2; color: #991b1b;">Skipped</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">No history yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- CUSTOM PAGINATION -->
        @if($history->hasPages())
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 20px;">
                @if($history->onFirstPage())
                    <span style="padding: 8px 16px; background: #eee; color: #999; border-radius: 6px;">Previous</span>
                @else
                    <a href="{{ $history->previousPageUrl() }}" style="padding: 8px 16px; background: #1e3a8a; color: white; border-radius: 6px; text-decoration: none;">Previous</a>
                @endif

                <span style="font-size: 14px; color: #666; font-weight: bold;">
                    Page {{ $history->currentPage() }} of {{ $history->lastPage() }}
                </span>

                @if($history->hasMorePages())
                    <a href="{{ $history->nextPageUrl() }}" style="padding: 8px 16px; background: #1e3a8a; color: white; border-radius: 6px; text-decoration: none;">Next</a>
                @else
                    <span style="padding: 8px 16px; background: #eee; color: #999; border-radius: 6px;">Next</span>
                @endif
            </div>
        @endif
    </div>
@endsection