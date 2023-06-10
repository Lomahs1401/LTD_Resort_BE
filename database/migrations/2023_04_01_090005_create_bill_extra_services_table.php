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
        Schema::create('bill_extra_services', function (Blueprint $table) {
            $table->id();
            $table->float('total_amount');
            $table->string('payment_method');
            $table->float('tax');
            $table->float('discount');
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('employee_id')->nullable()->constrained('employees');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_extra_services');
    }
};
