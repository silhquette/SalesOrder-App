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
        Schema::create('document_order', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->foreignUuid('document_id')
                ->references('id')->on('documents')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignUuid('order_id')
                ->references('id')->on('orders')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('additional')->nullable();
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
        Schema::dropIfExists('document_order');
    }
};
