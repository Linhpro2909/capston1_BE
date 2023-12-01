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
        Schema::create('ke_hoach_do_ans', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ke_hoach');
            $table->date('thoi_gian');
            $table->string('mo_ta');
            $table->string('image');
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
        Schema::dropIfExists('ke_hoach_do_ans');
    }
};
