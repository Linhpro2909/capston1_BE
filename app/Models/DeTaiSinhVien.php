<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTaiSinhVien extends Model
{
    use HasFactory;

    protected $table = "de_tai_sinh_viens";
    protected $fillable = [
        'id_nhom',
        'ten_de_tai',
        'mo_ta',
        'ngon_ngu_lap_trinh',
        'tinh_trang',
        'is_done',
        'thoi_gian_ket_thuc',
        'id_casptone'
    ];
}
