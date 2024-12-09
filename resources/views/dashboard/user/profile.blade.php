@extends('layouts.main')

@section('container')

<section class="user-section text-center text-white d-flex align-items-center border border-2 border-light my-3">
    <div class="card w-100 text-start">
        <div class="card-header">
            <h4 class="card-title">User Profile: <small class="text-muted">{{ $user->username }}</small></h4>
        </div>
        <div class="card-body lh-1">
            <img src="https://dummyimage.com/680x480/3e3e3e/fff&text={{ $user->username }}" alt="user profile" class="img-thumbnail" loading="lazy" title="user profile: {{ $user->username }}" onerror="this.onerror=null;this.src='/images/no-image-available.jpg';">
            <div class="user-details my-3 p-2">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th scope="row">Username</th>
                            <td><span class="me-1">:</span> {{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Name</th>
                            <td><span class="me-1">:</span> {{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><span class="me-1">:</span> {{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Role</th>
                            <td><span class="me-1">:</span> {{ $user->role }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Joined At</th>
                            <td><span class="me-1">:</span> {{ $user->created_at->format('d F Y H:i A') }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Last Updated At</th>
                            <td><span class="me-1">:</span> {{ $user->updated_at->format('d F Y H:i A') }}</td>
                        </tr>
                        <tr>    
                            <th scope="row">Posts has been created</th>
                            <td><span class="me-1">:</span> {{ $user->posts->count() }} Post</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<a href="{{ url()->previous() }}" class="btn btn-primary border-1 border-dark" title="Link: redirect to user page"><< Back</a>

@endsection