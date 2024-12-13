<?php

namespace App\Http\Controllers;

use App\Models\Comment_reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comments;
use App\Models\Posts;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = [
            'title' => 'Comments',
            'subtitle' => 'All comments',
            'breadcrumbs' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
                'Manage Comments' => '/comments/manage'
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

        return view('dashboard.comment.index')->with([
            ...$resources,
            'comments' => Comments::whereHas('post', function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
                ->with(['author', 'post'])
                ->get()
                ->groupBy(fn($comment) => $comment->post->title),
            'reported_comments' => Comment_reports::whereHas('comments', function ($query) {
                $query->whereHas('post', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            })
                ->with(['comments.post'])
                ->get()
                ->groupBy(fn($report) => $report->comments->post->title)
        ]);
    }

    /**
     * Method for reporting a specific comment.
     */
    public function report(Request $request, string $id)
    {
        // Check if the report is exists
        if (Comment_reports::where('comment_id', $id)->where('user_id', Auth::user()->id)->exists()) {
            return back()->with('message', toast('You already reported this comment', 'error'));
        }

        $validator = Validator::make(
            $request->all(),
            [
                'type' => 'required|string',
                'description' => 'nullable|string',
            ]
        );

        if ($validator->fails()) {
            return back()->with('message', toast('Invalid reporting comment details', 'error'));
        }

        $validated = $validator->validate();

        Comments::where('id', $id)->update(['is_reported' => true]);

        Comment_reports::create([
            ...$validated,
            'comment_id' => $id,
            'user_id' => Auth::user()->id
        ]);

        return back()->with('message', toast('Comment reported successfully!', 'success'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $slug)
    {
        $post = Posts::where('slug', $slug)->firstOrFail();

        $validator = Validator::make(
            $request->only('description'),
            [
                '_captcha' => 'required|string|same:' . session('captcha.text'),
                'description' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return redirect('/blog/' . $post->slug)->with('message', toast('Invalid creating comment details', 'error'));
        }

        $validated = $validator->validated();

        Comments::create([
            'post_id' => $post->id,
            'user_id' => Auth::user()->id,
            'description' => ucfirst($validated['description']),
        ]);

        return redirect('/blog/' . $post->slug . '#comments')->with('message', toast('Comment created successfully!', 'success'));
    }

    /**
     * Method for replying a speccific comment 
     */
    public function reply(Request $request, string $id)
    {
        $comment = Comments::findOrFail($id);
        $post = Posts::select('slug')->where('id', $comment->post_id)->first();

        $validator = Validator::make(
            $request->only('description'),
            [
                'description' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return redirect('/blog/' . $post->slug)->with('message', toast('Invalid replying comment details', 'error'));
        }

        $validated = $validator->validate();
        Comments::create([
            'post_id' => $comment->post_id,
            'user_id' => Auth::user()->id,
            'parent_id' => $id,
            'description' => $validated['description'],
        ]);

        return redirect('/blog/' . $post->slug . '#comments')->with('message', toast('Your reply created successfully!', 'success'));
    }

    /**
     * Get the specified resource.
     */
    public function get(string $id)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 10);

        if (empty($offset)) {
            return response()->json([
                'success' => false,
                'message' => 'Comments not found!',
                'data' => null
            ], 404);
        }

        $comments = Comments::where('post_id', $id)->offset($offset)->limit($limit)->get();

        return $comments ? response()->json([
            'success' => true,
            'message' => 'Comments found successfully!',
            'data' => $comments
        ]) :
            response()->json([
                'success' => false,
                'message' => 'Comments not found!',
                'data' => null
            ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
