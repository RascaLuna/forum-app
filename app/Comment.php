<?php

namespace App;

use App\Http\Requests\CommentRequest;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id', 'comment'
    ];

    public function user() {
        return $this->belongsTo(\App\User::class, 'user_id');
    }
}
