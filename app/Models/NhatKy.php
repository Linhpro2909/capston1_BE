<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhatKy extends Model
{
    use HasFactory;

    protected $table = "nhat_kys";
    protected $fillable = [
        "mo_ta",
        "id_sinh_vien",
        "ten_nhat_ky",
        "thoi_gian",
        "tinh_trang",
        "id_nhom",
        "ten_file",
        "file",
    ];
}
