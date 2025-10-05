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
        Schema::create('blood_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_stock_id')->constrained('blood_stocks')->onDelete('cascade');
            $table->enum('type', ['in', 'out']); // in = stok masuk, out = stok keluar
            $table->integer('quantity');
            $table->string('source_destination'); // Sumber stok masuk / tujuan stok keluar
            $table->string('reference_number')->nullable(); // Nomor referensi (misal: no. permintaan, no. penerimaan)
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_stock_transactions');
    }
};
