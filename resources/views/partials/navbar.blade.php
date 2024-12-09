
<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom border-1 border-dark shadow-sm sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand me-5" href="/">
            <img src="/images/RPL_Skandakra_Logo_Type_modified.svg" class="logo" alt="Logo" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" id="toggleNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse offcanvas-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ set_active('home')}}" href="/" title="Link: redirect to home page" aria-current="{{ set_active('home') ? 'page' : 'false' }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ set_active('about')}}" href="/about" title="Link: redirect to about page" aria-current="{{ set_active('about') ? 'page' : 'false' }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ set_active('contact')}}" href="/contact" title="Link: redirect to contact page" aria-current="{{ set_active('contact') ? 'page' : 'false' }}">Contact</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ is_active_query('*category=*') }}" href="javascript:void(0)" id="navbarDropdownCategories" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Link: to open categories section and list of categories" aria-current="{{ is_active_query('*category=*') ? 'page' : 'false' }}">
                        Categories
                    </a>
                    <ul class="dropdown-menu shadow-sm" aria-labelledby="navbarDropdownCategories">
                        @foreach ($categories as $category)
                            <li><a class="dropdown-item {{ is_active_query('*category=' . $category->slug) }}" href="/posts?{{ http_build_query(array_merge(request()->except('page'), ['category' => $category->slug])) }}" title="Link: redirect to posts by category '{{ $category->name }}'" aria-current="{{ is_active_query('*category=' . $category->slug) ? 'page' : 'false' }}">#{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </li> 
                <li class="nav-item">
                    <a class="nav-link {{ set_active('posts*') }}" href="/posts?page=1" title="Link: redirect to blog page or list of posts" aria-current="{{ set_active('posts*', 'post*') ? 'page' : 'false' }}">Blog</a>
                </li>
            </ul>
            @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle bg-light border-0 px-0" href="javascript:void(0)" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Link: to open user profile" aria-current="{{ set_active('dashboard') ? 'page' : 'false' }}">
                            <img src="/images/no-image-available.jpg" 
                            width="40" 
                            height="40" 
                            class="rounded-circle border border-2 me-2" 
                            alt="Profile image of user: {{ auth()->user()->username }}"
                            title="Profile image of user: {{ auth()->user()->username }}"
                            data-img-preview
                            data-img-preview-title="Profile image of user: {{ auth()->user()->username }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='/images/no-image-available.jpg';">
                            <span class="text-muted">Welcome, {{ auth()->user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu shadow-sm" aria-labelledby="navbarDropdownUser">
                            <li><a class="dropdown-item" href="/user/profile/{{ auth()->user()->username }}" title="Link: redirect to profile page" aria-current="{{ set_active('profile') ? 'page' : 'false' }}">Profile</a></li>
                            <li><a class="dropdown-item" href="/signout" title="Link: action to signout or logging out" aria-current="{{ set_active('signout') ? 'page' : 'false' }}" onclick="return confirm('Are you sure you want to sign out?')">Sign Out</a></li>
                        </ul>
                    </li>
                </ul>
            @else
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/signin" title="Link: redirect to login page" aria-current="{{ set_active('signin') ? 'page' : 'false' }}">Sign In</a>
                        <a class="nav-link" href="/signup" title="Link: redirect to register page" aria-current="{{ set_active('signup') ? 'page' : 'false' }}">Sign Up</a>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
