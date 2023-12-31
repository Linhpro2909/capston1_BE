<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sinh_viens', function (Blueprint $table) {
            $table->id();
            $table->string("ten_sinh_vien");
            $table->string("ma_sinh_vien")->unique();
            $table->string("so_dien_thoai");
            $table->integer("id_nien_khoa");
            $table->float("diem_gpa");
            $table->string("tinh_trang")->default(0);
            $table->string("password");
            $table->string("gmail");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sinh_viens');
    }
};
