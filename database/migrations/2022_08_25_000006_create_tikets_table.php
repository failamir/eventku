<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiketsTable extends Migration
{
    public function up()
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no_tiket')->nullable();
            $table->string('checkin')->nullable();
            $table->longText('notes')->nullable();
            $table->string('qr')->nullable();
            $table->string('status')->nullable();
            $table->string('status_payment')->nullable();
            $table->string('type_payment')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
