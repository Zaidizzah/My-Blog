<?php

namespace App\Http\Controllers;

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
        //
    }

    /**
     * Method for reporting a specific comment.
     */
    public function report()
    {
        //
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
                'description' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return redirect('/post')->with('message', toast('Invalid creating comment details', 'error'));
        }

        $validated = $validator->validated();

        Comments::create([
            'post_id' => $post->id,
            'user_id' => Auth::user()->id,
            'description' => $validated['description'],
        ]);

        return redirect('/posts/' . $post->slug . '#comments')->with('message', toast('Comment created successfully!', 'success'));
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
            return redirect('/post')->with('message', toast('Invalid replying comment details', 'error'));
        }

        $validated = $validator->validate();
        Comments::create([
            'post_id' => $comment->post_id,
            'user_id' => Auth::user()->id,
            'parent_id' => $id,
            'description' => $validated['description'],
        ]);

        return redirect('/posts/' . $post->slug . '#comments')->with('message', toast('Your reply created successfully!', 'success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
