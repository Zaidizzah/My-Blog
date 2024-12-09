@extends('layouts.main')

@section('container')
    <section class="contact-section text-center text-white d-flex align-items-center border border-2 border-light my-3">
        <div class="card w-100">
            <div class="card-body">
                <h2 class="card-title">{{ $title }} Page</h2>
                <p class="card-text">Ini adalah halaman {{ strtolower($title) }}</p>
            </div>
        </div>
    </section>
@endsection