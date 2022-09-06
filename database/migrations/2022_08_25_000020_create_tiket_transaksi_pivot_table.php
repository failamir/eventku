<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiketTransaksiPivotTable extends Migration
{
    public function up()
    {
        Schema::create('tiket_transaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('transaksi_id');
            $table->foreign('transaksi_id', 'transaksi_id_fk_7132843')->references('id')->on('transaksis')->onDelete('cascade');
            $table->unsignedBigInteger('tiket_id');
            $table->foreign('tiket_id', 'tiket_id_fk_7132843')->references('id')->on('tikets')->onDelete('cascade');
        });
    }
}
