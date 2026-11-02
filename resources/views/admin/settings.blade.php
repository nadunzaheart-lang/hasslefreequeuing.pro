@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'System Settings')
@section('page-description', 'Configure your queuing system preferences.')

@section('content')

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            
            <!-- GENERAL SETTINGS -->
            <div class="window-card" style="padding: 25px;">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; color: #1e3a8a; border-bottom: 1px solid #eee; padding-bottom: 10px;">General & Display</h3>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">College Name</label>
                    <input type="text" name="college_name" value="{{ $settings['college_name'] }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Office Name</label>
                    <input type="text" name="office_name" value="{{ $settings['office_name'] }}" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Next in Queue Count</label>
                        <input type="number" name="display_next_count" value="{{ $settings['display_next_count'] }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Refresh Speed (ms)</label>
                        <input type="number" name="display_refresh_speed" value="{{ $settings['display_refresh_speed'] }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                </div>
            </div>

            <!-- QUEUE RULES -->
            <div class="window-card" style="padding: 25px;">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; color: #1e3a8a; border-bottom: 1px solid #eee; padding-bottom: 10px;">Queue Rules</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Daily Reset Time</label>
                        <input type="time" name="daily_reset_time" value="{{ $settings['daily_reset_time'] }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Ticket Digits</label>
                        <select name="ticket_digits" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                            <option value="3" {{ $settings['ticket_digits'] == '3' ? 'selected' : '' }}>3 Digits (001)</option>
                            <option value="4" {{ $settings['ticket_digits'] == '4' ? 'selected' : '' }}>4 Digits (0001)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- VOICE SETTINGS -->
            <div class="window-card" style="padding: 25px;">
                <h3 style="margin: 0 0 15px 0; font-size: 18px; color: #1e3a8a; border-bottom: 1px solid #eee; padding-bottom: 10px;">Voice Announcements</h3>
                
                <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <div>
                        <strong style="display: block; font-size: 14px; color: #333;">Enable Voice</strong>
                    </div>
                    <input type="checkbox" name="voice_enabled" value="1" {{ $settings['voice_enabled'] == '1' ? 'checked' : '' }} style="width: 20px; height: 20px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px;">
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Repeat Count</label>
                        <input type="number" name="voice_repeat" value="{{ $settings['voice_repeat'] }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Pause (ms)</label>
                        <input type="number" name="voice_pause" value="{{ $settings['voice_pause'] }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px;">Voice Speed (0.5 = Slow, 1.5 = Fast)</label>
                    <input type="number" step="0.05" name="voice_speed" value="{{ $settings['voice_speed'] }}" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;">
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="window-card" style="padding: 25px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <h3 style="margin: 0 0 10px 0; font-size: 20px; color: #1e3a8a;">Save Configuration</h3>
                <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Apply these settings to the Kiosk, Staff, and TV Display.</p>
                <button type="submit" class="primary-button" style="padding: 15px 50px; font-size: 16px; width: 100%;">
                    Save Changes
                </button>
            </div>

        </div>
    </form>
@endsection