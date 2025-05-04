<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'u_id',
        'file_name',
        'thumb_name',
        'title',
        'type_flag',
        'kanri_flag',
    ];
}
