<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    use HasFactory;
    protected $table='sinh_viens';
    protected $fillable=[
        'ten_sinh_vien',
        'ma_sinh_vien',
        'so_dien_thoai',
        'diem_gpa',
    ];
}
