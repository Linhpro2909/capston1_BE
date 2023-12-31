<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('casptones', function (Blueprint $table) {
            $table->id();
            $table->string("ten_casptone");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('casptones');
    }
};
