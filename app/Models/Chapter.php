<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        
        'subject_id',
        'name'

    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
        
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'chapter_subject', 'chapter_id', 'subject_id');
    }

}
