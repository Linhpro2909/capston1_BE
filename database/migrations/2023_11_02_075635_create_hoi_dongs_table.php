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
        Schema::create('hoi_dongs', function (Blueprint $table) {
            $table->id();
            $table->string('Ten_Hoi_Dong');
            $table->string('Ten_Chu_Tich');
            $table->string('Ten_Thu_ky');
            $table->string('Ten_Uy_Vien');
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
        Schema::dropIfExists('hoi_dongs');
    }
};
