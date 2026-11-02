<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Adds Windows 01-05 (letters A-E) only if the windows table is empty,
    // so the "Assigned Window" list on the Staff Accounts page is never blank.
    public function up(): void
    {
        if (DB::table('windows')->count() > 0) {
            return;
        }

        $now = now();
        foreach (['A', 'B', 'C', 'D', 'E'] as $i => $letter) {
            DB::table('windows')->insert([
                'window_number' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'name' => 'Registrar ' . ($i + 1),
                'letter_prefix' => $letter,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        // Nothing to undo: the windows table itself is dropped by its own migration.
    }
};