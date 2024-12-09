@extends('layouts.main')

@section('container')

<section class="category-section text-center text-white d-flex align-items-center border border-2 border-light my-3">
    <div class="card w-100 text-start">
        <div class="card-header">
            <h4 class="card-title">Manage Categories</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive-md">
                <table class="table table-hover table-borderless" aria-label="Table: Categories" title="Table: Categories">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <th scope="row" data-id="{{ $category->id }}">{{ $loop->iteration }}</th>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->created_at->format('d F Y H:i A') }}</td>
                                <td>
                                    <div class="btn-group border border-1 border-dark">
                                        <a href="javascript:void(0);" class="btn btn-warning btn-edit" title="Button: to edit category '{{ $category->name }}'">Edit</a>
                                        <a href="/category/delete/{{ $category->id }}" class="btn btn-danger" title="Button: to delete category '{{ $category->name }}'" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $categories->onEachSide(2)->links('vendor.pagination.custom') }}
        </div>
    </div>
</section>

<section class="category-form text-center text-white d-flex align-items-center border border-2 border-light my-3">
    <div class="card w-100 text-start">
        <div class="card-header">
            <h4 class="card-title">Form Create Category</h4>
        </div>
        <form action="/category/store" method="POST" id="categoryForm">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name"
                    minlength="4"
                    maxlength="100" placeholder="Category Name" 
                    autocomplete="given-name" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" 
                    value="{{ old('name') }}" 
                    required>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Please fill in the form above</span>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary border-1 border-dark" id="btnCreateCategory" title="Button: to create new category" aria-disabled="false" onclick="return confirm('Are you sure want to create this category?')">Create New Category</button>
                        <button type="submit" class="btn btn-warning border-1 border-dark" id="btnUpdateCategory" title="Button: to update category" aria-disabled="true" onclick="return confirm('Are you sure want to update this category?')" disabled>Update Category</button>
                        <button type="reset" class="btn btn-light border-1 border-dark" id="btnResetForm" title="Button: to reset form">Reset</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@endsection