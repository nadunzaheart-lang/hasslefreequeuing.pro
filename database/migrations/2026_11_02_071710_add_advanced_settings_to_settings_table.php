<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Queue Rules
            $table->string('daily_reset_time')->default('00:00');
            $table->string('ticket_digits')->default('3');
            
            // Voice Settings
            $table->string('voice_repeat')->default('3');
            $table->string('voice_pause')->default('1500'); 
            $table->string('voice_speed')->default('0.85');
            
            // Display Settings
            $table->string('display_next_count')->default('5');
            $table->string('display_refresh_speed')->default('1000'); 
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'daily_reset_time', 
                'ticket_digits', 
                'voice_repeat', 
                'voice_pause', 
                'voice_speed', 
                'display_next_count', 
                'display_refresh_speed'
            ]);
        });
    }
};