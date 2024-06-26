<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    protected $table = 'topics';

    use HasFactory;

    use SoftDeletes;

    protected $fillable = [

        'chapter_id',
        'name'

    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    // public function chapters()
    // {
    //     return $this->belongsToMany(Chapter::class, 'topic_chapter', 'topic_id','chapter_id');
    // }
    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'topic_chapter', 'topic_id', 'chapter_id');
    }
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
