@extends('layouts.main')

@section('container')
    {{-- Postingan --}}
    <section class="--post-container" aria-label="post: {{ $post->title }}">
        <article class="--post-wrapper" title="Article: {{ $post->title }} by {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}">
            <div class="--post-image">
                <img src="{{ $post->image ? asset('storage/images/posts/' . $post->image) : 'https://dummyimage.com/680x480/3e3e3e/fff&text=' . $post->title }}" 
                class="img-thumbnail" 
                data-img-preview 
                data-img-preview-title="{{ $post->title }}" 
                title="Thumbnail: {{ $post->title }}" 
                loading="lazy" 
                aria-label="Thumbnail: {{ $post->title }}" 
                onerror="this.onerror=null;this.src='/images/no-image-available.jpg';"
                alt="Thumbnail: {{ $post->title }}">
            </div>
            <div class="--post-metadata">
                <h3><a href="/posts/{{ $post->slug }}">{{ $post->title }}</a></h3>
                <p>
                    By. {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}
                    <small class="text-muted">
                        At {{ explode(' ', $post->created_at)[0] }}, {{ $post->created_at->format('H:i A') }} or {{ $post->created_at->diffForHumans() }}
                    </small>
                    <a href="/posts/{{ $post->slug }}#comments" class="float-end text-muted no-permalink">Comments: 0</a>
                </p>
            </div>
            <div class="--post-excerpt">
                <p>
                    {!! $post->body !!}
                </p>
                <div class="--post-tags">
                    <span>Tag 1</span>
                    <span>Tag 2</span>
                    <span>Tag 3</span>
                </div>
            </div>
        </article>
    </section>

    <section class="comments-container" id="comments" aria-label="comments section" title="Comments section for post '{{ $post->title }}'">
        <h2 class="h2 text-dark mb-3" id="comments">Comments</h2>

        @include('partials.comments', [
            'comments' => $post->comments
        ])
    </section>

    <section class="post-prev-next-section bg-light border border-2 border-dark p-2 mb-3" aria-label="post prev next section">
        <div class="d-flex align-items-center justify-content-between gap-1" title="Previous Post: {{ $previous_post ? $previous_post->title : 'No Previous Post' }} | Next Post: {{ $next_post ? $next_post->title : 'No Next Post' }}">
            <a href="{{ $previous_post ? '/posts/' . $previous_post->slug : 'javascript:void(0);' }}" class="no-permalink post-prev-next-link{{ $previous_post ? '' : ' text-muted' }}" title="Link: redirect to previous post '{{ $previous_post ? $previous_post->title : 'No Previous Post' }}">
                @if($previous_post)
                    <img src="{{ $previous_post->image ? asset('storage/images/posts/' . $previous_post->image) : '/images/no-image-available.jpg' }}" 
                        class="img-thumbnail border-dark rounded-0" 
                        width="40px" height="40px"
                        alt="Thumbnail: {{ $previous_post->title }}" 
                        loading="lazy" 
                        data-img-preview
                        data-img-preview-title="Thumbnail: {{ $previous_post->title }}"
                        onerror="this.onerror=null;this.src='/images/no-image-available.jpg';">
                @endif
                <span class="post-prev-next-title ms-1">
                    {{ $previous_post ? $previous_post->title : 'No Previous Post' }}
                </span>
            </a>
            <a href="{{ $next_post ? '/posts/' . $next_post->slug : 'javascript:void(0);' }}" class="no-permalink post-prev-next-link{{ $next_post ? '' : ' text-muted' }}" title="Link: redirect to next post '{{ $next_post ? $next_post->title : 'No Next Post' }}">
                <span class="post-prev-next-title me-1">
                    {{ $next_post ? $next_post->title : 'No Next Post' }}
                </span>
                @if ($next_post)
                    <img src="{{ $next_post->image ? asset('storage/images/posts/' . $next_post->image) : '/images/no-image-available.jpg' }}" 
                        class="img-thumbnail border-dark rounded-0" 
                        width="40px" height="40px" 
                        alt="Thumbnail: {{ $next_post->title }}" 
                        loading="lazy" 
                        data-img-preview
                        data-img-preview-title="Thumbnail: {{ $next_post->title }}"
                        onerror="this.onerror=null;this.src='/images/no-image-available.jpg';">
                @endif
            </a>
        </div>
    </section>

    <a href="/posts?page=1" class="btn btn-primary border-1 border-dark" title="Link: redirect to blog page"><< Back</a>
@endsection