<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles'; // テーブル名（省略可だけど明記しておくと安心）

    protected $fillable = [
        'u_id',
        'yubin',
        'jusho1',
        'jusho2',
        'jusho3',
        'tel',
        'biko',
        'kari_flag',
        'kanri_flag',
    ];
}
