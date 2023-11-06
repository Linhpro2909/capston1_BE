<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoiDong extends Model
{
    use HasFactory;
    protected $table='hoi_dongs';
    protected $fillable=[
        'Ten_Hoi_Dong',
        'Ten_Chu_Tich',
        'Ten_Thu_ky',
        'Ten_Uy_Vien',
        'Thanh_Vien_Hoi_Dong',
    ];
}
