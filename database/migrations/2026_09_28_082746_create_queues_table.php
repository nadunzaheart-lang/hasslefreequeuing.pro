<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('queues', function (Blueprint $table) {
    $table->id();
    $table->string('queue_number');      // e.g. "A-109"
    $table->string('student_name');      // e.g. "Juan Dela Cruz"
    $table->string('student_id');        // e.g. "2024-00125"
    $table->string('transaction');       // e.g. "Enrollment Validation"
    $table->string('status')->default('waiting'); // waiting / serving / completed
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
