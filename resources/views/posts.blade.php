@extends('layouts.main')

@section('container')

    @if ($posts->isNotEmpty()) 
        <section class="--posts-wrapper">
            <section class="--first-posts-section-wrapper">
                <section class="--post-container" aria-label="post">
                    <article class="--post-wrapper" title="Article: {{ $posts[0]->title }} by {{ isset($posts[0]->author->name) ? $posts[0]->author->name : 'Unknown' }} in {{ $posts[0]->category->name }}">
                        <div class="--post-image">
                        <img
                            src="{{ $posts[0]->image ? asset('storage/images/posts/' . $posts[0]->image) : 'https://dummyimage.com/680x480/3e3e3e/fff&text=' . $posts[0]->title }}"
                            class="img-thumbnail"
                            data-img-preview
                            data-img-preview-title="{{ $posts[0]->title }}"
                            title="Thumbnail: {{ $posts[0]->title }}"
                            loading="lazy"
                            aria-label="Thumbnail: {{ $posts[0]->title }}"
                            onerror="this.onerror=null;this.src='/images/no-image-available.jpg';"
                            alt="Thumbnail: {{ $posts[0]->title }}"
                            />
                        </div>
                        <div class="--post-metadata">
                            <h3><a href="/blog/{{ $posts[0]->slug }}">{{ $posts[0]->title }}</a></h3>
                            <p>
                                By. {{ isset($posts[0]->author->name) ? $posts[0]->author->name : 'Unknown' }} in {{ $posts[0]->category->name }}
                                <small class="text-muted">
                                    At {{ explode(' ', $posts[0]->created_at)[0] }}, {{ $posts[0]->created_at->format('H:i A') }} or {{ $posts[0]->created_at->diffForHumans() }}
                                </small>
                                <a href="/blog/{{ $posts[0]->slug }}#comments" class="float-end text-muted no-permalink">Comments: {{ $posts[0]->count() }}</a>
                            </p>
                        </div>
                        <div class="--post-excerpt">
                            <p>
                                {!! $posts[0]->excerpt !!}
                            </p>
                            <div class="--post-tags">
                                <span>Tag 1</span>
                                <span>Tag 2</span>
                                <span>Tag 3</span>
                            </div>
                        </div>
                    </article>
                </section>  
            </section>
            
            <section class="--posts-section-wrapper">
                <section class="--post-container" aria-label="posts">
                    @foreach ($posts->skip(1) as $post)
                        <article class="--post-wrapper" title="Article: {{ $post->title }} by {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}">
                            <div class="--post-image">
                                <img
                                src="{{ $post->image ? asset('storage/images/posts/' . $post->image) : 'https://dummyimage.com/680x480/3e3e3e/fff&text=' . $post->title }}"
                                class="img-thumbnail"
                                data-img-preview
                                data-img-preview-title="{{ $post->title }}"
                                title="Thumbnail: {{ $post->title }}"
                                loading="lazy"
                                aria-label="Thumbnail: {{ $post->title }}"
                                onerror="this.onerror=null;this.src='/images/no-image-available.jpg';"
                                alt="Thumbnail: {{ $post->title }}"
                                />
                            </div>
                            <div class="--post-metadata">
                                <h3><a href="/blog/{{ $post->slug }}">{{ $post->title }}</a></h3>
                                <p>
                                    By. {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}
                                    <small class="text-muted">
                                        At {{ explode(' ', $post->created_at)[0] }}, {{ $post->created_at->format('H:i A') }} or {{ $post->created_at->diffForHumans() }}
                                    </small>
                                    <a href="/blog/{{ $post->slug }}#comments" class="float-end text-muted no-permalink">Comments: {{ $posts[0]->count() }}</a>
                                </p>
                            </div>
                            <div class="--post-excerpt">
                                <p>
                                    {!! $post->excerpt !!}
                                </p>
                                <div class="--post-tags">
                                    <span>Tag 1</span>
                                    <span>Tag 2</span>
                                    <span>Tag 3</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>

                {{ $posts->onEachSide(2)->links('vendor.pagination.custom') }}
            </section>

            <section class="--sidebar-posts-wrapper">
                <!-- Search Section -->
                <section class="search-section mb-3" aria-label="search section">
                    <form action="/blog" method="get" title="Form: Search for posts">
                        <div class="input-group flex-nowrap">
                            <input type="search" class="form-control border-1 border-dark rounded-0" list="search-list" title="Input: Search for posts by title or author" placeholder="Search by title or author..." aria-label="Search" value="{{ request('search') }}">
                            <datalist id="search-list">
                                @foreach ($authors as $author)
                                    <option value="{{ $author->name }}">{{ $author->name }}</option>
                                @endforeach
                            </datalist>
                            <button class="btn btn-outline-dark rounded-0" type="submit" title="Button: Click to search for posts">Search</button>
                        </div>
                        @if (request('category')) 
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        
                        @if (request('author')) 
                        <input type="hidden" name="author" value="{{ request('author') }}">
                        @endif
                    </form>
                </section>

                <!-- Category Section -->
                <section class="category-section mb-3" aria-label="category section">
                    <ul class="list-group">
                        <li class="list-group-item bg-light border-1 border-dark rounded-0">
                            <h2 class="h4 text-dark mb-3">Categories</h2>
                        </li>
                        @foreach ($categories as $category)
                            <li class="list-group-item border-top-0 border-bottom border-1 border-dark rounded-0">
                                <a href="/blog?category={{ $category->slug }}" title="Link: redirect to posts by category '{{ $category->name }}'">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <!-- New 7 Posts Section -->
                <section class="new-posts-section border border-bottom-0 border-1 border-dark mb-3" aria-label="new 7 posts section">
                    <h2 class="h4 text-dark bg-light border-bottom border-dark p-3 mb-0">New Posts</h2>
                    @foreach ($new_posts as $post)
                        <article class="--post-wrapper border-0 border-bottom border-dark" title="Article: {{ $post->title }} by {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}">
                            <div class="--post-image border-0">
                                <img
                                src="{{ $post->image ? asset('storage/images/posts/' . $post->image) : 'https://dummyimage.com/680x480/3e3e3e/fff&text=' . $post->title }}"
                                class="img-thumbnail rounded-0"
                                data-img-preview
                                data-img-preview-title="{{ $post->title }}"
                                title="Thumbnail: {{ $post->title }}"
                                loading="lazy"
                                aria-label="Thumbnail: {{ $post->title }}"
                                onerror="this.onerror=null;this.src='/images/no-image-available.jpg';"
                                alt="Thumbnail: {{ $post->title }}"
                                />
                            </div>
                            <div class="--post-content p-2">
                                <h3 class="h5"><a href="/blog/{{ $post->slug }}" title="Link: {{ $post->title }}">{{ $post->title }}</a></h3>
                                <p>
                                    By. {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}
                                    <small class="text-muted">
                                        At {{ explode(' ', $post->created_at)[0] }}, {{ $post->created_at->format('H:i A') }} or {{ $post->created_at->diffForHumans() }}
                                    </small>
                                    <a href="/blog/{{ $post->slug }}#comments" class="float-end text-muted no-permalink">Comments: 0</a>
                                </p>
                            </div>
                        </article>
                    @endforeach
                </section>

                <!-- Arsip Section -->
                <section class="arsip-sectio mb-3" aria-label="arsip section">
                    <ul class="list-group">
                        <li class="list-group-item bg-light border-bottom border-1 border-dark rounded-0">
                            <h2 class="h4 text-dark mb-3">Archive</h2>
                        </li>
                        @foreach ($archives as $archive)
                            <li class="list-group-item border-top-0 border-bottom border-1 border-dark rounded-0">
                                <a href="/blog?month={{ $archive->month }}&year={{ $archive->year }}" title="Link: redirect to posts in {{ generate_month_name($archive->month) }} {{ $archive->year }}">{{ generate_month_name($archive->month) }} {{ $archive->year }}</a>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <!-- Subscribe Section -->
                <section class="subscribe-section mb-3" aria-label="subscribe section">
                    <div class="card rounded-0 border-1 border-dark">
                        <div class="card-body">
                            <h2 class="card-title">Subscribe</h2>
                            <p class="card-text">Subscribe to our newsletter to get the latest news and updates.</p>
                            <form action="/subscribe" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                    <button class="btn btn-primary" type="submit">Subscribe</button>
                                </div>
                                <div class="invalid-tooltip">
                                    Please provide a valid email.
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </section>
        </section>
    @else
        <section class="post-section text-center text-white d-flex align-items-center border border-2 border-light mb-3">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Tidak Ada Postingan</h2>
                    <p class="card-text">{{  request('search') ? 'Tidak ada postingan yang sesuai dengan pencarian Anda saat ini.' : 'Tidak ada postingan yang tersedia saat ini.' }}</p>
                </div>
            </div>
        </section>
    @endif

    @if (array_intersect(array_keys(request()->query()), ['search', 'category', 'author', 'month', 'year']))
        <a href="/blog?page=1" class="btn btn-primary border-1 border-dark mt-2"><< Back</a>
    @endif

@endsection