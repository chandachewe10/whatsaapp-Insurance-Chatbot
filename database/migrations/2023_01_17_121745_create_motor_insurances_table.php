<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motor_insurances', function (Blueprint $table) {
            $table->id();
            $table->string('client_whatsapp_number');
            $table->string('client_name');
            $table->string('vehicle_insured_name');
            $table->string('quarter');
            $table->string('vehicle_model');
            $table->string('vehicle_registration_number');

            $table->string('sum_insured');
            $table->string('insurance_type');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motor_insurances');
    }
};
