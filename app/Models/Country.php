<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    use HasFactory;
    protected $fillable = [
        'name',

    ];

    public function board()
    {
        return $this->belongsToMany(Board::class);
    }

}
