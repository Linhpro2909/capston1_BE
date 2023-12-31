<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('de_tai_sinh_viens', function (Blueprint $table) {
            $table->id();
            $table->integer('id_nhom');
            $table->string('ten_de_tai');
            $table->text('mo_ta');
            $table->string('ngon_ngu_lap_trinh');
            $table->integer('tinh_trang')->default(0)->comment("0: đang chờ duyệt, 1: duyệt");
            $table->integer('is_done')->default(0)->comment("0: chua hoàn thành, 1: đã hoàn thành");
            $table->integer('id_casptone');
            $table->date('thoi_gian_ket_thuc')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('de_tai_sinh_viens');
    }
};
