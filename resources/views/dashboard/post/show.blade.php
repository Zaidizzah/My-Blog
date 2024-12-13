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

    <section class="comments-container" id="comments" aria-label="comments section" aria-labelledby="commentsLabel" title="Comments section for post '{{ $post->title }}'">
        <div class="comments-header">
            <h2 class="h2 text-dark" id="commentsLabel">Comments</h2>
            <button class="btn btn-light btn-expand-comments border-1 border-dark" title="Button: Click to expand comments section" aria-label="Button: Click to expand comments section" role="button"><i class="fa fa-expand fa-fw"></i></button>
        </div>

        {!! generate_comments_section($post->comments) !!}
    </section>

    <section class="comment-form-container" aria-label="comment form section">
        <h2 class="h2 text-dark mb-3">Post a Comment</h2>
        
        <form class="comment-form" action="/comments/store/{{ $post->slug }}" method="post">
            @csrf

            <div class="input-group mb-3">
                <img src="{{ session('captcha.image') }}" 
                    class="img-thumbnail border-dark" 
                    title="Captcha Image" 
                    data-img-preview
                    data-img-preview-title="Captcha Image"
                    loading="lazy" 
                    alt="Captcha Image">
                <input type="text" class="form-control border-dark" name="_captcha" maxlength="4" placeholder="Captcha" required>
            </div>
            <input type="hidden" name="replyng_to">
            <div class="form-control border-dark mb-2" id="replying-to"><span class="text-primary">Replying to:</span></div>
            <textarea class="form-control border-dark" 
                name="description" 
                id="description" 
                cols="30" rows="3"
                placeholder="Leave a comment here"
                data-bs-toggle="tooltip"
                data-bs-placement="top"
                data-bs-title="Press enter to submit comment here, but don't forget to be nice!"
                required></textarea>
            <button type="submit" class="btn btn-primary border border-1 border-dark" title="Button: Click to post comment" onclick="return confirm('Are you sure want to post this comment?')">Post Comment</button>
        </form>
    </section>

    <a href="/post" class="btn btn-primary border-1 border-dark" title="Link: redirect to posts management page"><< Back</a>
    
@endsection