<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Returns the signin page.
     * 
     * @return \Illuminate\View\View
     */
    public function signInPage(): \Illuminate\View\View
    {
        $resources = [
            'title' => 'Sign In',
            'css' => [
                [
                    'href' => 'signin.css',
                    'base_path' => '/css/'
                ]
            ]
        ];

        return view('signin')->with($resources);
    }

    /**
     * Authenticates the user and logs them in.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signIn(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('message', toast('Login was successful', 'success'));
        }

        return back()->with('message', toast('Invalid login details', 'error'))->withInput();
    }

    /**
     * Returns the signup page.
     * 
     * @return \Illuminate\View\View
     */
    public function signUpPage(): \Illuminate\View\View
    {
        $resources = [
            'title' => 'Sign Up',
            'css' => [
                [
                    'href' => 'signup.css',
                    'base_path' => '/css/'
                ]
            ]
        ];

        return view('signup')->with($resources);
    }

    /**
     * Creates a new user, logs them in, and redirects to the home page.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signUp(Request $request): \Illuminate\Http\RedirectResponse
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
            return redirect('/signup')->with('message', toast('Invalid signup details', 'error'))->withInput();
        }

        $validated = $validator->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['username'] = ucwords($validated['username']);
        $validated['name'] = ucwords($validated['name']);
        $validated['role'] = 'Author';

        User::create($validated);

        return redirect('/signin')->with('message', toast('Account created successfully!', 'success'));
    }

    /**
     * Logs the user out by flushing the session and redirects to the home page.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signOut(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
