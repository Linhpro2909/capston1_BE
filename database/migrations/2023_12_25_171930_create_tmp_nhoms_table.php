<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tmp_nhoms', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sinh_vien');
            $table->integer('id_casptone');
            $table->integer('id_diem');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tmp_nhoms');
    }
};
