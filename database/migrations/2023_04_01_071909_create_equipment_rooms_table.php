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
        Schema::create('equipment_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->foreignId('equipment_type_id')->constrained('equipment_room_types')->onUpdate('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_rooms');
    }
};
