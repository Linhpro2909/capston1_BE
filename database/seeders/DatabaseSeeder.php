<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->call(AdminSeeder::class);
        $this->call(NienKhoaSeeder::class);
        $this->call(CasptoneSeeder::class);
        $this->call(SinhVienSeeder::class);
        $this->call(DiemSeeder::class);
        $this->call(GiangVienSeeder::class);
    }
}
