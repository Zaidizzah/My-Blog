<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = array(
        'title',
        'category_id',
        'user_id',
        'slug',
        'excerpt',
        'body',
        'image'
    );
    protected $with = ['category', 'author', 'comments'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id');
    }

    /**
     * Apply filters to the query based on the given filters array.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return void
     *
     * Filters:
     * - 'search': Filter posts by title containing the search term.
     * - 'category': Filter posts by category slug.
     * - 'author': Filter posts by author's username, hyphens replaced with spaces.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('author', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        })->when($filters['category'] ?? false, function ($query, $category) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('slug', $category);
            });
        })->when($filters['author'] ?? false, function ($query, $author) {
            $query->whereHas('author', function ($query) use ($author) {
                $query->where('username', str_replace('-', ' ', $author));
            });
        });
    }

    /**
     * Filter posts by user. Supports filtering by user id, username, and email.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User $user
     * @param string $column
     * @param string|null $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUser($query, $user, string $column = 'user_id', ?string $value = null)
    {
        return $query->whereHas('author', function ($query) use ($user, $column, $value) {
            if ($value == null) {
                $query->where($column, $user->id)
                    ->orWhere('username', $user->username)
                    ->orWhere('email', $user->email);
            } else {
                $query->where($column, $value);
            }
        });
    }
}
