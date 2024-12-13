<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Support\Str;

class BlogController extends Controller
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

        if (request('month') && request('year')) {
            $titleParts[] = 'In <small class="text-muted fst-italic">' . generate_month_name(request('month')) . ' ' . request('year') . '</small>';
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
            'posts' => Posts::latest()->filter(request(['search', 'category', 'author', 'month', 'year']))->paginate(21)->withQueryString(),
            'new_posts' => Posts::latest()->take(7)->get(),
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
    public function show(string $slug): \Illuminate\View\View
    {
        $post = Posts::where('slug', $slug)->firstOrFail();

        // Set captcha on session
        session(['captcha' => generate_captcha()]);

        $previous_post = $post->where('id', '<', $post->id)->without(['author', 'category'])->latest()->first();
        $next_post = $post->where('id', '>', $post->id)->without(['author', 'category'])->first();

        $resources = [
            'title' => $post->title,
            'subtitle' => "Post: $post->title",
            'breadcrumb' => [
                'Home' => '/',
                'Posts' => '/posts',
                $post->title => '/posts/' . $post->slug,
            ],
            'css' => [
                [
                    'href' => 'comments.css',
                    'base_path' => '/resources/comments/css/'
                ]
            ],
            'javascript' => [
                [
                    'src' => 'comments.js',
                    'base_path' => '/resources/comments/js/'
                ]
            ]
        ];

        return view('post')->with([
            ...$resources,
            'post' => $post,
            'previous_post' => $previous_post ?: null,
            'next_post' => $next_post ?: null
        ]);
    }
}
