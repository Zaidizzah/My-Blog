@auth
    {!! generate_comments_section($comments) !!}

    <section class="comment-form-container" aria-label="comment form section">
        <h2 class="h2 text-dark mb-3">Post a Comment</h2>

        <form class="comment-form" action="/comment/store/{{ $post->slug }}" method="post">
            @csrf

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
@endauth
@guest
<p class=" text-danger">* You must be logged in to post a comment.</p>
@endguest