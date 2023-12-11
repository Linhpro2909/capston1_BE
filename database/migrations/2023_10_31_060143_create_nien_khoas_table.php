<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nien_khoas', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nien_khoa');
            $table->date('thoi_gian_bat_dau');
            $table->date('thoi_gian_ket_thuc');
            $table->string('ma_nien_khoa');
            $table->integer('tinh_trang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nien_khoas');
    }
};
