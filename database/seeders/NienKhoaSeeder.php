<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NienKhoaSeeder extends Seeder
{
    public function run()
    {
        DB::table('nien_khoas')->delete();

        DB::table('nien_khoas')->truncate();

        DB::table('nien_khoas')->insert([
            [
                'ten_nien_khoa'         => "Khóa 26",
                'thoi_gian_bat_dau'     => "2019",
                'thoi_gian_ket_thuc'    => "2023",
                'tinh_trang'            => 1,
                'ma_nien_khoa'          => "K26",
            ],
            [
                'ten_nien_khoa'         => "Khóa 27",
                'thoi_gian_bat_dau'     => "2020",
                'thoi_gian_ket_thuc'    => "2024",
                'tinh_trang'            => 1,
                'ma_nien_khoa'          => "K27",
            ],
        ]);
    }
}
