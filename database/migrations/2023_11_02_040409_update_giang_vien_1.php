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
        Schema::table('GiangVien', function (Blueprint $table) {
            $table->string('Name')->after('id');
            $table->date('Date_of_birth')->after('Name');
            $table->string('Address')->after('Date_of_birth');
            $table->string('don_vi_cong_tac')->after('Address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('GiangVien', function (Blueprint $table) {
            //
        });
    }
};
