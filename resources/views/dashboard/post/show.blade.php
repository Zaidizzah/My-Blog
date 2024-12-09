@extends('layouts.main')

@section('container')

    <section class="--post-container" aria-label="post: {{ $post->title }}">
        <article class="--post-wrapper" 
        aria-label="post: {{ $post->title }} by {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}" 
        title="Article: {{ $post->title }} by {{ isset($post->author->name) ? $post->author->name : 'Unknown' }} in {{ $post->category->name }}">
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

    <a href="/post" class="btn btn-primary border-1 border-dark" title="Link: redirect to posts management page"><< Back</a>
    
@endsection