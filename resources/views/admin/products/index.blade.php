@extends('admin.layouts.admin')

@section('content')
<h1>Product Catalog</h1>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- 1. ADD NEW PRODUCT BUTTON --}}
{{-- Yeh button '/admin/products/create' route par jayega --}}
<a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">
    ➕ Add New Product
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category ID</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->category_id }}</td>
            <td>
                {{-- 2. EDIT BUTTON --}}
                {{-- Yeh button '/admin/products/{id}/edit' route par jayega --}}
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                    ✏️ Edit
                </a>

                {{-- 3. DELETE BUTTON (Optional, lekin useful) --}}
                {{-- Iske liye tumhe delete route aur controller method banana padega --}}
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this product?')">
                        🗑️ Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection