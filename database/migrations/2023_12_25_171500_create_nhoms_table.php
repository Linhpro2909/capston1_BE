<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nhoms', function (Blueprint $table) {
            $table->id();
            $table->string("ten_nhom");
            $table->string("ma_nhom");
            $table->string("id_giang_vien");
            $table->string("id_hoi_dong")->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nhoms');
    }
};
