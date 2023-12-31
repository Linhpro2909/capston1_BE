<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasptoneSeeder extends Seeder
{
    public function run()
    {
        DB::table('casptones')->delete();

        DB::table('casptones')->truncate();

        DB::table('casptones')->insert([
            [
                'ten_casptone' => "Casptone 1"
            ],
            [
                'ten_casptone' => "Casptone 2"
            ],
        ]);
    }
}
