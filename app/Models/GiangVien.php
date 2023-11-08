<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiangVien extends Model
{
    use HasFactory;
    protected $table='GiangVien';
    protected $fillable=[
        'Name',
        'Date_of_birth',
        'Address',
        'don_vi_cong_tac',
        'ma_giang_vien'
    ];
}
