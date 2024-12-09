<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Support\Str;

class PostsController extends Controller
{
    /**
     * Shows all posts.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $titleParts = [];

        if (request('search')) {
            $titleParts[] = 'Search results for <small class="text-muted fst-italic">"' . e(request('search')) . '"</small>';
        }

        if (request('category')) {
            $titleParts[] = 'In <small class="text-muted fst-italic">"' . Str::title(request('category')) . '"</small>';
        }

        if (request('author')) {
            $author = str_replace('-', ' ', request('author'));
            $titleParts[] = 'By <small class="text-muted fst-italic">"' . Str::title($author) . '"</small>';
        }

        $resources = [
            'title' => 'Posts',
            'subtitle' => empty($titleParts) ? 'All Posts' : 'Posts - ' . implode(' ', $titleParts),
            'breadcrumb' => [
                'Home' => '/',
                'Posts' => '/posts',
            ],
        ];

        return view('posts')->with([
            ...$resources,
            'posts' => Posts::latest()->filter(request(['search', 'category', 'author']))->paginate(23)->withQueryString(),
            'authors' => User::select('name')->get(),
            'archives' => Posts::selectRaw('year(created_at) year, month(created_at) month, count(*) published')->groupBy('year', 'month')->orderByRaw('min(created_at) desc')->get()
        ]);
    }

    /**
     * Returns a single post by its slug.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(Posts $post): \Illuminate\View\View
    {
        $post = $post;

        $previous_post = Posts::where('id', '<', $post->id)->latest()->first();
        $next_post = Posts::where('id', '>', $post->id)->oldest()->first();

        $resources = [
            'title' => $post->title,
            'subtitle' => "Post: $post->title",
            'breadcrumb' => [
                'Home' => '/',
                'Posts' => '/posts',
                $post->title => '/posts/' . $post->slug,
            ],
        ];

        return view('post')->with([
            ...$resources,
            'post' => $post,
            'previous_post' => $previous_post,
            'next_post' => $next_post
        ]);
    }
}
