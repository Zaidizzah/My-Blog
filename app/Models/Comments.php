<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'description',
    ];

    protected $with = ['author'];

    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Comments::class, 'parent_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Comment_reports::class, 'comment_id', 'id');
    }
}
