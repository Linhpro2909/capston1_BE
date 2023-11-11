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
        Schema::create('de_tai_sinh_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ten_sinh_vien');
            $table->string('ma_so_sinh_vien');
            $table->string('ten_de_tai');
            $table->string('mo_ta');
            $table->string('ngon_ngu_lap_trinh');
            $table->integer('tinh_trang')->default(0);
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
        Schema::dropIfExists('de_tai_sinh_viens');
    }
};
