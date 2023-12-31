<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('diems', function (Blueprint $table) {
            $table->id();
            $table->string("id_sinh_vien");
            $table->integer("id_casptone")->default(1);
            $table->integer("id_nhom")->nullable();
            $table->float("diem_mentor")->nullable();
            $table->float("diem_chu_tich")->nullable();
            $table->float("diem_thu_ky")->nullable();
            $table->float("diem_uy_vien")->nullable();
            $table->integer("is_hoan_thanh")->default(0)->comment("0: chưa hoàn thành, 1: đã hoàn thành");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diems');
    }
};
