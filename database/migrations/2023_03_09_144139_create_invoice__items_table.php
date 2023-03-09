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
        Schema::create('invoice__items', function (Blueprint $table) {
             $table->id();
            $table->foreignId('invoice_id');
            $table->foreignId('product_id');
            $table->integer('quantity');
            $table->float('total_vat');
            $table->float('total_price');
            $table->float('total_amount_due');
            $table->float('tax_percentage');
          

            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice__items');
    }
};