@extends('layouts.main')

@section('container')

  <section class="post-form text-center text-white d-flex align-items-center border border-2 border-light mb-3">
    <div class="card w-100 text-start">
      <div class="card-header">
          <h4 class="card-title">Form Create Post</h4>
      </div>
      <form action="/post/store" method="POST" enctype="multipart/form-data">
        <div class="card-body">
          @csrf
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="title" class="form-label">Title:</label>
              <input type="text" class="form-control" id="title" name="title" 
              minlength="4"
              maxlength="100" placeholder="Post Title" 
              aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}"
              autocomplete="given-name" aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}" 
              value="{{ old('title') }}" 
              required>
            </div>
            <div class="col-md-6">
              <label for="slug" class="form-label">Slug:</label>
              <input type="text" class="form-control" id="slug" disabled>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="author" class="form-label">Author:</label>
              <input type="text" class="form-control" id="author" value="{{ auth()->user()->username }}" disabled>
            </div>
            <div class="col-md-6">
              <label for="category" class="form-label">Category:</label>
              <select class="form-select" id="category" name="category_id" aria-invalid="{{ $errors->has('category') ? 'true' : 'false' }}" required>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected aria-selected="true"' : 'aria-selected="false"' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <div class="image-preview-container" title="Thumbnail: No image available">
              <span class="image-preview-title">Thumbnail: <small class="small">No image available</small></span>
              <img src="/images/no-image-available.jpg" alt="Thumbnail: post image" class="image-preview img-thumbnail" data-img-preview data-img-preview-title="Thumbnail: No image available">
              <input type="file" class="form-control" id="image" name="image" accept="image/jpg, image/jpeg, image/png, image/gif, image/webp, image/svg+xml" max="3048" aria-invalid="{{ $errors->has('thumbnail') ? 'true' : 'false' }}">
            </div>
          </div>
          <div class="mb-3">
            <label for="content" class="form-label">Content:</label>
            <textarea class="form-control" id="content" name="content" rows="3" placeholder="Post Content" aria-invalid="{{ $errors->has('content') ? 'true' : 'false' }}">{{ old('content') }}</textarea>
          </div>
        </div>
        <div class="card-footer">
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Please fill in the form above</span>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary border-1 border-dark" id="btnCreatePost" title="Button: to create new post" aria-disabled="false">Create New Post</button>
                <button type="submit" class="btn btn-warning border-1 border-dark" id="btnUpdatePost" title="Button: to update post" aria-disabled="true" disabled>Update Post</button>
                <button type="reset" class="btn btn-light border-1 border-dark" id="btnResetForm" title="Button: to reset form" onclick="return confirm('Are you sure want to reset this form?')">Reset</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>

  @if(isset($posts) && $posts->isNotEmpty())
    <section class="--post-container"
      aria-label="posts">
      @foreach ($posts as $post)
        <article 
          class="--post-wrapper" title="Article: {{ $post->title }} by {{ $post->author->username }} in {{ $post->category->name }}" 
          aria-label="Article: {{ $post->title }} by {{ $post->author->username }} in {{ $post->category->name }}" 
          data-post-id="{{ $post->id }}">
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
            <h3><a href="/post/show/{{ $post->slug }}">{{ $post->title }}</a></h3>
            <p>
              By. {{ $post->author->name }} in {{ $post->category->name }}
              <small class="text-muted">
                At {{ explode(' ', $post->created_at)[0] }}, {{ $post->created_at->format('H:i A') }} or {{ $post->created_at->diffForHumans() }}
              </small>
              <span class="float-end text-muted ms-1">Comments: 0</span>
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
  @else
    <section class="post-section text-center text-white d-flex align-items-center border border-2 border-light mb-3">
      <div class="card w-100">
          <div class="card-body">
              <h2 class="card-title">Tidak Ada Postingan</h2>
              <p class="card-text">Anda belum memiliki postingan saat ini.</p>
          </div>
      </div>
    </section>
  @endif

@endsection