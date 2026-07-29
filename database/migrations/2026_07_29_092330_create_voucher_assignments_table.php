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
        Schema::create('t_voucher_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('crew_name');
            $table->string('crew_id');
            $table->string('flight_number');
            $table->date('flight_date');
            $table->string('aircraft_type');
            $table->string('seat_1');
            $table->string('seat_2');
            $table->string('seat_3');
            $table->timestamps();

            $table->foreign('aircraft_type')->references('aircraft_type')->on('m_aircraft_layouts');
            $table->unique(['flight_number', 'flight_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_voucher_assignments');
    }
};
