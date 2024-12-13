<aside class="sidebar" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <button class="btn btn-light rounded-0 border-1 border-dark border-top-0 border-start-0" type="button" id="toggleSidebar" title="Button: Click to open sidebar" aria-controls="sidebar">
        <i class="fas fa-user-cog fa-fw"></i>
    </button>
    <div class="sidebar-header">
        <h5 class="sidebar-title" id="sidebaLabel" title="Title: sidebar">Authentication Settings</h5>
    </div>
    <div class="sidebar-body">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link {{ set_active('dashboard*') }}" href="/dashboard" title="Link: redirect to dashboard page" aria-current="{{ set_active('dashboard*') ? 'page' : 'false' }}"><i class="fa fa-home fa-fw me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ set_active('post*') }}" href="/post" title="Link: redirect to post page" aria-current="{{ set_active('post*') ? 'page' : 'false' }}"><i class="fa fa-newspaper fa-fw me-2"></i> Manage Posts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ set_active('comment*') }}" href="/comments/manage" title="Link: redirect to comments page" aria-current="{{ set_active('comment*') ? 'page' : 'false' }}"><i class="fa fa-comments fa-fw me-2"></i> Manage Comments</a>
            </li>
            @can('access-admin')
                <li class="nav-item">
                    <a class="nav-link {{ set_active('category*') }}" href="/category" title="Link: redirect to category page" aria-current="{{ set_active('category*') ? 'page' : 'false' }}"><i class="fa fa-list fa-fw me-2"></i> Manage Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ set_active('user*') }}" href="/user" title="Link: redirect to user page" aria-current="{{ set_active('users*') ? 'page' : 'false' }}"><i class="fa fa-users fa-fw me-2"></i> Manage Users</a>
                </li>
            @endcan
        </ul>
    </div>
</aside>