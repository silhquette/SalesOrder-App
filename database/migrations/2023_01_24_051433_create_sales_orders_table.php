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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_id')
                ->references('id')->on('customers')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('nomor_po')->unique();
            $table->date('due_time');
            $table->date('tanggal_po');
            $table->smallInteger('ppn');
            $table->string('order_code')->unique();
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
        Schema::dropIfExists('sales_orders');
    }
};
