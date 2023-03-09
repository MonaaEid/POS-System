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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id(); 
            $table->string('name');
            $table->string('building_no');
            $table->string('street_name');
            $table->string('district');
            $table->string('city');
            $table->string('country');
            $table->string('postal_code');
            $table->string('additional_number');
            $table->string('vat_number');
            $table->string('other_buyer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};