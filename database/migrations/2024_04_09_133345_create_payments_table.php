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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('reference');

            $table->string('payment_method');
            $table->string('payment_method_type');

            $table->string('account_number')->nullable();
            $table->string('qr_code_data')->nullable();

            $table->string('payment_url');

            $table->integer('amount');
            $table->integer('fee');

            $table->timestamp('expired_date')->nullable();
            $table->enum('status', ['PENDING', 'SUCCESS', 'FAILED', 'CANCELED'])->default('PENDING');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
