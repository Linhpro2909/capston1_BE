<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diem extends Model
{
    use HasFactory;

    protected $table = "diems";
    protected $fillable = [
        "id_sinh_vien",
        "id_casptone",
        "id_nhom",
        "diem_mentor",
        "diem_chu_tich",
        "diem_thu_ky",
        "diem_uy_vien",
        "is_hoan_thanh",
    ];
}
