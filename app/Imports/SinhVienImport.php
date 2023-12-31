<?php

namespace App\Imports;

use App\Models\SinhVien;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SinhVienImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new SinhVien([
            'ten_sinh_vien' => $row['ten_sinh_vien'],
            'ma_sinh_vien' => $row['ma_sinh_vien'],
            'so_dien_thoai' => $row['so_dien_thoai'],
            'diem_gpa' => $row['diem_gpa'],
            'tinh_trang' => $row['tinh_trang'],
            'id_nien_khoa' => $row['id_nien_khoa'],
            'password' => bcrypt($row['password']),
            'diem_mentor' => $row['diem_mentor'],
            'diem_chu_tich' => $row['diem_chu_tich'],
            'diem_thu_ky' => $row['diem_thu_ky'],
            'diem_uy_vien' => $row['diem_uy_vien'],

        ]);
    }
}
