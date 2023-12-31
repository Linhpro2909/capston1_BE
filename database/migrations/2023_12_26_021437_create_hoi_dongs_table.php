<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hoi_dongs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_hoi_dong');
            $table->string('list_id_hoi_dong');
            $table->string('list_ma_nhom')->nullable();
            $table->integer('id_chu_tich');
            $table->integer('id_thu_ky');
            $table->integer('id_uy_vien');
            $table->date('thoi_gian');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hoi_dongs');
    }
};
