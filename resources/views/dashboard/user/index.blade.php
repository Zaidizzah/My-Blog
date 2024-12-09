@extends('layouts.main')

@section('container')

    <section class="user-section text-center text-white d-flex align-items-center border border-2 border-light my-3">
        <div class="card w-100 text-start">
            <div class="card-header">
                <h4 class="card-title">Manage Users</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-hover table-borderless" aria-label="Table: Users" title="Table: Users">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Username</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Joined At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th scope="row" data-id="{{ $user->id }}">{{ $loop->iteration }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d F Y H:i A') }}</td>
                                    <td>
                                        <div class="btn-group border border-1 border-dark">
                                            <a href="/user/profile/{{ $user->username }}" class="btn btn-primary" title="Button: to detail user '{{ $user->name }}'">Detail</a>
                                            <a href="javascript:void(0);" class="btn btn-warning btn-edit" title="Button: to edit user '{{ $user->name }}'">Edit</a>
                                            <a href="/user/delete/{{ $user->id }}" class="btn btn-danger" title="Button: to delete user '{{ $user->name }}'" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $users->onEachSide(2)->links('vendor.pagination.custom') }}
            </div>
        </div>
    </section>

    <section class="user-form text-center text-white d-flex align-items-center border border-2 border-light my-3">
        <div class="card w-100 text-start">
            <div class="card-header">
                <h4 class="card-title">Form Create User</h4>
            </div>
            <form action="/user/store" method="POST" id="userForm">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" class="form-control" id="username" name="username"
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="Maximum of 50 characters. Username must start with a letter and can only contain letters, numbers, and underscores. Example: 'johndoe', make sure it is unique and this not can be changed!"
                        title="Maximum of 50 characters. Username must start with a letter and can only contain letters, numbers, and underscores. Example: 'johndoe', make sure it is unique and this not can be changed!"
                        pattern="^[a-zA-Z][a-zA-Z0-9_]{0,49}$"
                        minlength="4"
                        maxlength="50" placeholder="Username" 
                        autocomplete="username" aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}" 
                        value="{{ old('username') }}" 
                        required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" maxlength="100" placeholder="Name" autocomplete="given-name" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" value="{{ old('name') }}" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role:</label>
                            <input type="text" class="form-control" id="role" aria-disabled="true" aria-invalid="false" value="Author" disabled>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="254" placeholder="Email" autocomplete="email" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" 
                        data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number. Example: 'Password#3529'"
                        title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number. Example: 'Password#3529'"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$$"
                        minlength="8" maxlength="16" placeholder="Password"
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        required>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Please fill in the form above</span>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary border-1 border-dark" id="btnCreateUser" title="Button: to create new user" aria-disabled="false" onclick="return confirm('Are you sure want to create this user?')">Create New User</button>
                            <button type="submit" class="btn btn-warning border-1 border-dark" id="btnUpdateUser" title="Button: to update user" aria-disabled="true" onclick="return confirm('Are you sure want to update this user?')" disabled>Update User</button>
                            <button type="reset" class="btn btn-light border-1 border-dark" id="btnResetForm" title="Button: to reset form">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    
@endsection