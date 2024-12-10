<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request and return the dashboard view.
     *  
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $resources = [
            'title' => 'Dashboard',
            'subtitle' => 'Dashboard',
            'breadcrumb' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
            ],
            'css' => [
                [
                    'href' => 'styles.css',
                    'base_path' => '/resources/dashboard/css/'
                ]
            ],
            'javascript' => [
                [
                    'src' => 'echarts.min.js',
                    'base_path' => '/resources/plugins/echarts/'
                ],
                [
                    'src' => 'scripts.js',
                    'base_path' => '/resources/dashboard/js/'
                ]
            ]
        ];

        $user = Auth::user();
        $count_categories = Category::count();
        $count_posts = Posts::where('user_id', $user->id)->count();

        return view('dashboard/index')->with([
            ...$resources,
            'user' => $user,
            'count_categories' => $count_categories,
            'count_posts' => $count_posts
        ]);
    }
}
