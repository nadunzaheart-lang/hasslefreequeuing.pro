@extends('layouts.app')

@section('title', 'Manage Accounts')
@section('page-title', 'Staff Accounts')
@section('page-description', 'Manage login accounts for registrar staff.')

@section('content')

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px;">

        <!-- ADD STAFF FORM -->
        <div class="window-card" style="height: fit-content;">
            <div class="window-header" style="padding-bottom: 10px; border-bottom: 1px solid var(--color-border);">
                <div>
                    <h3 style="margin: 0;">Add New Staff</h3>
                    <p style="font-size: 13px; color: var(--color-muted); margin-top: 5px;">Create a login for a window.</p>
                </div>
            </div>
            <div style="padding: 20px;">
                <form action="{{ route('admin.accounts.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Staff Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Maria Clara" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 6px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="maria@tcc.edu.ph" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 6px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 6px;">
                    </div>
                    @if($windows->isEmpty())
                        <div style="background: #fbf3e0; color: #8a5f10; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                            No windows exist yet. <a href="{{ route('admin.windows') }}" style="font-weight: bold; color: #8a5f10;">Add a window first</a>, then create the staff account.
                        </div>
                    @endif
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px;">Assigned Window</label>
                        <select name="window_number" required style="width: 100%; padding: 10px; border: 1px solid var(--color-border); border-radius: 6px;">
                            @foreach($windows as $win)
                                <option value="{{ $win->window_number }}" {{ old('window_number') == $win->window_number ? 'selected' : '' }}>
                                    {{ $win->name }} (Window {{ $win->window_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="primary-button" style="width: 100%; justify-content: center;" {{ $windows->isEmpty() ? 'disabled' : '' }}>+ Create Staff Account</button>
                </form>
            </div>
        </div>

        <!-- STAFF LIST -->
        <div class="queue-panel">
            <div class="panel-header">
                <div><h2>Registered Staff</h2><p>Newest accounts appear at the top.</p></div>
            </div>
            <div class="table-wrapper">
                <table class="queue-table">
                    <thead><tr><th>NAME</th><th>EMAIL</th><th>WINDOW</th><th>ACTION</th></tr></thead>
                    <tbody>
                        @forelse($staff as $s)
                            <tr>
                                <td><strong>{{ $s->name }}</strong></td>
                                <td>{{ $s->email }}</td>
                                <td>
                                    @if($s->window_number)
                                        <span class="status-badge" style="background: var(--color-bg); color: var(--color-text); border: 1px solid var(--color-border);">Window {{ $s->window_number }}</span>
                                    @else
                                        <span class="status-badge" style="background: #fee2e2; color: #991b1b;">Not Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.accounts.delete', $s->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: bold;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align: center; padding: 20px;">No staff added yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
