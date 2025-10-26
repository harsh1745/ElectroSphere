{{-- resources/views/admin/categories/index.blade.php --}}

@extends('admin.layouts.admin')

@section('content')
<h1>Category List</h1>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add New Category</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection