<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = [
        'id',
        'tag_name'
    ];

    public function posts()
    {
        return $this->belongsToMany(\App\Post::class);
    }
}
