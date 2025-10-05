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
        Schema::create('donor_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->foreignId('blood_donation_schedule_id')->nullable()->constrained('blood_donation_schedules')->onDelete('set null');
            $table->date('tanggal_donor');
            $table->string('lokasi');
            $table->unsignedInteger('jumlah_kantong')->default(1);
            $table->enum('status', ['pending', 'valid', 'ditolak'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donor_histories');
    }
};