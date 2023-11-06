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
        Schema::create('GiangVien', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('Date_of_birth');
            $table->string('Address');
            $table->string('don_vi_cong_tac');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GiangVien');
    }
};
