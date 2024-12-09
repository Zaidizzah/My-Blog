<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Posts;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\View\View
    {
        $resources = [
            'title' => 'Users Management',
            'subtitle' => 'Manage Users',
            'breadcrumb' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
                'Users Management' => '/user'
            ],
            'css' => [
                [
                    'href' => 'styles.css',
                    'base_path' => 'resources/user/css/'
                ]
            ],
            'javascript' => [
                [
                    'src' => 'scripts.js',
                    'base_path' => 'resources/user/js/'

                ]
            ]
        ];

        return view('dashboard.user.index')->with([
            ...$resources,
            'users' => User::paginate(5)->withQueryString()

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|string|min:4|max:100|regex:/^[a-zA-Z][a-zA-Z0-9_]{0,49}$/|unique:users',
                'name' => 'required|string|min:4|max:100',
                'email' => 'required|string|email|unique:users|max:254',
                'password' => [
                    'required',
                    Password::min(8)->max(16)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
                ]
            ],
        );

        if ($validator->fails()) {
            return redirect('/user')->with('message', toast('Invalid creating user details', 'error'))->withInput();
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['username'] = ucwords($validated['username']);
        $validated['name'] = ucwords($validated['name']);
        $validated['role'] = 'Author';

        User::create($validated);

        return redirect('/user')->with('message', toast('User created successfully!', 'success'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username): \Illuminate\View\View
    {
        $user = User::where('username', $username)->firstOrFail();

        $resources = [
            'title' => 'User Profile',
            'subtitle' => 'User Profile',
            'breadcrumb' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
                'Users Management' => '/user',
                'User Profile',
                $user->username => '/user/profile/' . $user->id
            ],
        ];

        return view('dashboard.user.profile')->with([
            ...$resources,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail($id);

        $validator = Validator::make(
            $request->only(['name', 'email']),
            [
                'name' => 'required|string|min:4|max:100',
                'email' => 'required|string|email|unique:users,email,' . $user->id,
            ],
        );

        if ($validator->fails()) {
            return redirect('/user')->with('message', toast('Invalid updating user details', 'error'))->withInput();
        }

        $validated = $validator->validated();
        $validated['name'] = ucwords($validated['name']);

        $user->update($validated);

        return redirect('/user')->with('message', toast('User updated successfully!', 'success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Delete user image
        if ($user->image) {
            Storage::delete('images/users/' . $user->image);
        }

        // Change user_id field value from posts table to NULL
        Posts::where('user_id', $user->id)->update(['user_id' => NULL]);

        return redirect('/user')->with('message', toast('User deleted successfully!', 'success'));
    }
}
