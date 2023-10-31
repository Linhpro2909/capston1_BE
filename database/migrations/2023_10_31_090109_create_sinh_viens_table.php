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
            $table->string('ten_sinh_vien');
            $table->string('ma_sinh_vien');
            $table->integer('so_dien_thoai');
            $table->float('diem_gpa');
            $table->timestamps();
        });
    }


};
