<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuiMail extends Model
{
    use HasFactory;
    protected $table = "gui_mails";
    protected $fillable = [
        "noi_dung_gui",
    ];
}
