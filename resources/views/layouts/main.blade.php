@php
    $segments = url()->full();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="My Blog">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Blog | {{ $title }}</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Image Preview -->
    <link rel="stylesheet" href="{{ asset('resources/plugins/imgpreview/css/imgpreview.css') }}">

    @isset($css)
        @generate_tags('link', $css)
    @endisset
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('partials.navbar')
    
    @includeWhen(auth()->check(), 'partials.sidebar')

    <main class="content-wrapper container">

        @includeWhen(
            route_check(['home', 'about', 'contact', 'posts']),
            'partials.hero-section'
            )

        @isset($subtitle, $breadcrumb) 
            {!! breadcrumb_builder($breadcrumb, $subtitle) !!}
        @endif

        @yield('container')

        @includeWhen(
            $errors->any() || 
            session('message'), 
            'partials.toast-message'
            )

    </main>

    @include('partials.footer')

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="{{ asset('resources/plugins/jQuery/jQuery.js') }}"></script>

    <!-- Image Preview -->
    <script src="{{ asset('resources/plugins/imgpreview/js/imgpreview.js') }}"></script>

    <script src="{{ asset('js/scripts.js') }}"></script>

    @isset($javascript)
        @generate_tags('script', $javascript)
    @endisset
</body>
</html>