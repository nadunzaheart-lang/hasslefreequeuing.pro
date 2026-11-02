<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Creates the `windows` table. The existing migration
    // 2026_11_02_073726_create_windows_table.php only SEEDS Windows 01-05 and
    // assumes this table already exists, so this one must run right before it
    // (hence the timestamp ...073725).
    public function up(): void
    {
        // Guard: skip if the table already exists (e.g. on the backend dev's machine).
        if (Schema::hasTable('windows')) {
            return;
        }

        Schema::create('windows', function (Blueprint $table) {
            $table->id();
            $table->string('window_number')->unique();
            $table->string('name');
            $table->string('letter_prefix', 1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('windows');
    }
};
