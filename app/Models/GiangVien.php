<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    use HasFactory;
    protected $table='giang_viens';
    protected $fillable=[
        'ma_giang_vien',
        'ten_giang_vien',
        'ngay_thang_nam_sinh',
        'dia_chi',
        'dia_chi_cong_tac',
    ];
}
