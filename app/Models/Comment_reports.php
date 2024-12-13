<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment_reports extends Model
{
    use HasFactory;

    protected $table = 'comment_reports';

    protected $fillable = [
        'comment_id',
        'user_id',
        'description',
        'status',
    ];

    protected $with = ['comments', 'author'];

    public function comments()
    {
        return $this->belongsTo(Comments::class, 'comment_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
