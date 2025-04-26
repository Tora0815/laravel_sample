<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // リレーション用

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

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

    /**
     * users テーブルとのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'u_id');
    }
}
