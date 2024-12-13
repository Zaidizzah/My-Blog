@extends('layouts.main')

@section('container')

    <section class="form-signup w-100 m-auto">
        <form action="/signup" method="POST">
            <h3 class="h3 mb-3 fw-normal">Please sign up</h3>
        
            @csrf
            <input type="text" name="username" class="form-control rounded-top rounded-0 border-bottom-0 border-dark" id="username"
            data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="Maximum of 50 characters. Username must start with a letter and can only contain letters, numbers, and underscores. Example: 'johndoe', make sure it is unique and this not can be changed!"
            title="Maximum of 50 characters. Username must start with a letter and can only contain letters, numbers, and underscores. Example: 'johndoe', make sure it is unique and this not can be changed!"
            pattern="^[a-zA-Z][a-zA-Z0-9_]{0,49}$"
            minlength="4"
            maxlength="50" placeholder="Username" 
            autocomplete="username" aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}" 
            value="{{ old('username') }}" 
            required>
            <input type="text" name="name" class="form-control rounded-0 border-bottom-0 border-dark" id="name" minlength="4" maxlength="100" placeholder="Name" autocomplete="name" autocomplete="given-name" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" value="{{ old('name') }}" required>
            <input type="email" name="email" class="form-control rounded-0 border-bottom-0 border-dark" id="email" maxlength="254" placeholder="Email Address" autocomplete="email" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" value="{{ old('email') }}" required>
            <input type="password" name="password" class="form-control rounded-bottom rounded-0 border-dark" id="password"
            data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number. Example: 'Password#3529'"
            title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number. Example: 'Password#3529'"
            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$$"
            minlength="8" maxlength="16" placeholder="Password"
            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
            required>

            <div class="input-group mt-2">
                <img src="{{ session('captcha.image') }}" 
                    class="img-thumbnail border-dark" 
                    title="Captcha Image" 
                    data-img-preview
                    data-img-preview-title="Captcha Image"
                    loading="lazy" 
                    alt="Captcha Image">
                <input type="text" class="form-control border-dark" name="_captcha" maxlength="4" placeholder="Captcha" required>
            </div>
            
            <button class="btn btn-primary w-100 py-2" type="submit" title="Button: Click to sign up">Sign up</button>
        </form> 
        <div class="account-link d-block text-center"><small class="text-muted">Already have an account? <a class="no-permalink" href="/signin" title="Link: Click to redirect to sign in form">Sign in</a></small></div>
    </section>

@endsection