<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalisirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legalisir', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pelegalisir_id')->unsigned();
            $table->bigInteger('permintaan_legalisir_id')->unsigned();
            $table->string('file_ijazah_legalisir')->nullable();
            $table->date('berlaku_sampai');
            $table->text('kode_legalisir')->nullable();
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
        Schema::dropIfExists('legalisir');
    }
}
