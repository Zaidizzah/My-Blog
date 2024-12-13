<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use App\Models\Posts;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = [
            'title' => 'Posts Management',
            'subtitle' => 'Manage Posts',
            'breadcrumb' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
                'Posts Management' => '/post'
            ],
            'css' => [
                [
                    'href' => 'styles.css',
                    'base_path' => '/resources/post/css/'
                ]
            ],
            'javascript' => [
                [
                    'src' => 'tinymce.min.js',
                    'base_path' => '/resources/plugins/tinymce/'
                ],
                [
                    'src' => 'uploadpreview.js',
                    'base_path' => '/resources/plugins/uploadpreview/'
                ],
                [
                    'src' => 'scripts.js',
                    'base_path' => '/resources/post/js/'
                ]
            ]
        ];

        return view('dashboard.post.index')->with([
            ...$resources,
            'posts' => Posts::byUser(Auth::user())->latest()->paginate(6)->withQueryString(),
            'categories' => Category::all()
        ]);
    }

    /**
     * Store and Update a image of post in storage.
     */
    private function storeImage(Request $request, string $action = 'store', string $id = null)
    {
        $image = $request->file('image');

        if (!$image) {
            return [
                'status' => false,
                'image' => null,
                'message' => 'Image not found in request.',
            ];
        }

        if ($action == 'update') {
            $post = Posts::select('image')->findOrFail($id);

            if ($post->image) {
                Storage::delete('images/posts/' . $post->image);
            }
        }

        $image_name = Str::random(25) . '.' . $image->getClientOriginalExtension();
        Storage::putFileAs('images/posts', $image, $image_name);

        return [
            'status' => true,
            'image' => $image_name,
            'message' => 'Image uploaded successfully.'
        ];
    }

    /**
     * Validate user action on post.
     * 
     * This function will validate if the user is allowed to perform
     * an action on the post by checking if the user is the author of the post. If the post doesn't exist or the user
     * is not the author of the post, it will throw a 404 error.
     * 
     * @param string $id
     * @return \App\Models\Posts
     */
    private function validateUserAction(string $field, string $value)
    {
        $post = Posts::where($field, $value)->where('user_id', Auth::user()->id)->firstOrFail();

        return $post;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:150|unique:posts',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image' => [
                    'required',
                    File::image()
                        ->types(['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp'])
                        ->max(3548)
                        ->dimensions(Rule::dimensions()->maxWidth(1600)->maxHeight(2564))
                ]
            ]
        );

        if ($validator->fails()) {
            return redirect('/post')->with('message', toast('Invalid creating post details', 'error'))->withInput();
        }

        $image = $this->storeImage($request);

        $validated = $validator->validated();
        $validated['user_id'] = Auth::user()->id;
        $validated['title'] = ucfirst($validated['title']);
        $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 252);
        if ($image['status']) {
            $validated['image'] = $image['image'];
        }
        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = now();
        $validated['body'] = strip_tags(ucfirst($validated['content']));
        unset($validated['content']);

        Posts::create($validated);

        return redirect('/post')->with('message', toast('Post created successfully!', 'success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = $this->validateUserAction('slug', $slug);

        // Set captcha on session
        session(['captcha' => generate_captcha()]);

        $resources = [
            'title' => $post->title,
            'subtitle' => "Post: $post->title",
            'breadcrumb' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
                'Posts Management' => '/post',
                $post->title => '/post/' . $post->slug
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

        return view('dashboard.post.show')->with([
            ...$resources,
            'post' => $post
        ]);
    }

    /**
     * Handle a request to get specified resource for editing.
     */
    public function get()
    {
        $post = Posts::without(['category', 'author'])
            ->select('id', 'user_id', 'category_id', 'title', 'slug', 'image', 'body')
            ->where('user_id', Auth::user()->id)->find(request('id'));

        if (empty($post)) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found!',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post found successfully!',
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = $this->validateUserAction('id', $id);

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:150|unique:posts,title,' . $id,
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image' => [
                    File::image()
                        ->types(['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp'])
                        ->max(3548)
                        ->dimensions(Rule::dimensions()->maxWidth(1600)->maxHeight(2564))
                ]
            ]
        );

        if ($validator->fails()) {
            return redirect('/post')->with('message', toast('Invalid updating post details', 'error'))->withInput();
        }

        $image = $this->storeImage($request, 'update', $id);

        $validated = $validator->validated();
        $validated['title'] = ucfirst($validated['title']);
        $validated['excerpt'] = Str::limit(strip_tags($validated['content']), 252);
        if ($image['status']) {
            $validated['image'] = $image['image'];
        }
        $validated['slug'] = Str::slug($validated['title']);
        $validated['body'] = strip_tags(ucfirst($validated['content']));
        unset($validated['content']);

        $post->update($validated);

        return redirect('/post')->with('message', toast('Post updated successfully!', 'success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = $this->validateUserAction('id', $id);

        if ($post->image) {
            Storage::delete('images/posts/' . $post->image);
        }

        $post->delete();

        return redirect('/post')->with('message', toast('Post deleted successfully!', 'success'));
    }
}
