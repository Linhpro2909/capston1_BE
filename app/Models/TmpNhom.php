<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TmpNhom extends Model
{
    use HasFactory;

    protected $table = "tmp_nhoms";
    protected $fillable = [
        'id_sinh_vien',
        'id_casptone',
        'id_diem',
    ];
}
