<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'description',
    ];

    protected $with = ['author'];

    public function post()
    {
        return $this->belongsTo(Posts::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
