<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawsTable extends Migration
{
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_withdraw')->nullable();
            $table->date('tanggal_withdraw')->nullable();
            $table->integer('jumlah_withdraw')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
