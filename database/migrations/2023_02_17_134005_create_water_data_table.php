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
        Schema::create('water_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->integer('order_input');
            $table->integer('volume_awal')->nullable();
            $table->integer('volume_akhir');
            $table->string('price');
            $table->date('date');
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('member_id')->references('id')
                  ->on('members')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('water_data');
    }
};
