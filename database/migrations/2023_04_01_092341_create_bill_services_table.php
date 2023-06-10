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
        Schema::create('bill_services', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->float('total_amount');
            $table->date('book_time');
            $table->string('payment_method');
            $table->dateTime('pay_time')->nullable();
            $table->dateTime('checkin_time')->nullable();
            $table->dateTime('cancel_time')->nullable();
            $table->float('tax');
            $table->float('discount');
            $table->string('bill_code')->nullable();
            $table->foreignId('service_id')->constrained('services');
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
        Schema::dropIfExists('bill_services');
    }
};
