<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nhat_kys', function (Blueprint $table) {
            $table->id();
            $table->string("ten_nhat_ky");
            $table->integer("id_sinh_vien");
            $table->date("thoi_gian");
            $table->string("mo_ta");
            $table->integer("tinh_trang");
            $table->string("file")->nullable();
            $table->string("ten_file")->nullable();
            $table->integer("id_nhom");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nhat_kys');
    }
};
