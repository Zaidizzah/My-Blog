@extends('layouts.main')

@section('container')

    <section class="form-signin w-100 m-auto">
        <form action="/signin" method="POST">
            <h3 class="h3 mb-3 fw-normal">Please sign in</h3>
        
            @csrf
            <input type="email" name="email" class="form-control rounded-top rounded-0 border-bottom-0 border-dark" id="email" maxlength="254" placeholder="Email Address" autocomplete="email" value="{{ old('email') }}"  autofocus required>
            <input type="password" name="password" class="form-control rounded-bottom rounded-0 border-dark" id="password"
            data-bs-toggle="tooltip" data-bs-placement="top"
            data-bs-title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number. Example: 'Password#3529'!"
            title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number. Example: 'Password#3529'!"
            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$$"
            minlength="8" maxlength="16" placeholder="Password"
            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
            required>
            <div class="input-group">
                <img src="{{ session('captcha.image') }}" 
                    class="img-thumbnail border-dark" 
                    title="Captcha Image" 
                    data-img-preview
                    data-img-preview-title="Captcha Image"
                    loading="lazy" 
                    alt="Captcha Image">
                <input type="text" class="form-control border-dark" name="_captcha" maxlength="4" placeholder="Captcha" required>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit" title="Button: Click to sign in">Sign in</button>
        </form> 
        <div class="account-link d-block text-center"><small class="text-muted">Don't have an account? <a class="no-permalink" href="/signup" title="Link: Click to redirect to sign up form">Sign Up</a></small></div>
    </section>

@endsection