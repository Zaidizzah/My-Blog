@extends('layouts.main')

@section('container')
    <section class="reported-comments-container expanded mt-3" id="reportedComments" aria-label="reported comments section" aria-labelledby="reportedCommentsLabel" title="Manage Reported Comments section">
        <div class="comments-header">   
            <h2 class="h2 text-dark" id="reportedCommentsLabel">Manage Reported Comments</h2>
            <button class="btn btn-light btn-expand-comments border-1 border-dark" title="Button: Click to expand comments section" aria-label="Button: Click to expand comments section" role="button"><i class="fa fa-expand fa-fw"></i></button>
        </div>

            {!! generate_comments_section($reported_comments) !!}
    </section>

    <section class="comments-container expanded mt-3" id="comments" aria-label="comments section" aria-labelledby="commentsLabel" title="Manage Comments section">
        <div class="comments-header">
            <h2 class="h2 text-dark" id="commentsLabel">Manage Comments</h2>
            <button class="btn btn-light btn-expand-comments border-1 border-dark" title="Button: Click to expand comments section" aria-label="Button: Click to expand comments section" role="button"><i class="fa fa-expand fa-fw"></i></button>
        </div>

        {!! generate_comments_section($comments) !!}
    </section>
@endsection