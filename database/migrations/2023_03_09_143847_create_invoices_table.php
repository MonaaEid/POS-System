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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->dateTime('invoice_issue_date');
            $table->date('date_of_supply');
            $table->string('branch');
            $table->string('salesman_name');
            $table->float('total_vat')->nullable();
            $table->float('total_price')->nullable();
            $table->float('total_amount_due')->nullable();

            $table->foreignId('buyer_id');
            $table->foreignId('seller_id');
            $table->foreign('buyer_id')->references('id')->on('buyers');
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};