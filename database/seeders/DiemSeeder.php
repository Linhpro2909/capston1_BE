<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiemSeeder extends Seeder
{
    public function run()
    {
        DB::table('diems')->delete();

        DB::table('diems')->truncate();

        DB::table('diems')->insert([
            [
                'id_sinh_vien'  => 1,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 2,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 3,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 4,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 5,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 6,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 7,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
            [
                'id_sinh_vien'  => 8,
                'id_casptone'   => 1,
                'is_hoan_thanh' => 0,
            ],
        ]);
    }
}
