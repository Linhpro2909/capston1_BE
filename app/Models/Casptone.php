<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casptone extends Model
{
    use HasFactory;

    protected $table = "casptones";
    protected $fillable = [
        "ten_casptone"
    ];
}
