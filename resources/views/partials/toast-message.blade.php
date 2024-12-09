<div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if ($errors->any())
        <div class="toast error show fade" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="15000">
            <div class="toast-header">
                <strong class="me-auto">Error!</strong>
                <small class="text-muted">Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                @foreach ($errors->all() as $error)
                    <p class="text-danger">* {!! $error !!}</p>
                @endforeach
            </div>
        </div>
    @endif

    @if (session('message')) {!! session('message') !!} @endif
</div>