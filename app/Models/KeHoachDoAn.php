<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeHoachDoAn extends Model
{
    use HasFactory;
    protected $table='ke_hoach_do_ans';
    protected $fillable=[
        'ten_ke_hoach',
        'thoi_gian',
        'mo_ta',
        'file',
        'filepath',
        'tinh_trang',
    ];
}
