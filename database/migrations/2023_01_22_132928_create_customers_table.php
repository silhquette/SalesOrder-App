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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('npwp');
            $table->string('name');
            $table->string('email')->nullable();
            $table->integer('term');
            $table->string('address')->unique();
            $table->string('phone')->nullable();
            $table->string('contact')->nullable();
            $table->string('npwp_add')->nullable();
            $table->uuid('code')->unique();
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
        Schema::dropIfExists('customers');
    }
};
