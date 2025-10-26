{{-- resources/views/admin/products/edit.blade.php --}}

@extends('admin.layouts.admin')

@section('content')

<h1>Edit Product: {{ $product->name }}</h1>

{{-- Form action update route par jayega, aur current product ID use karega --}}
<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT') {{-- PUT method Laravel ko update action call karne ke liye batata hai --}}

    {{-- Product Name: Existing value load hogi --}}
    <div class="form-group">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name"
            value="{{ old('name', $product->name) }}" required>
        @error('name') <span class="error-message">{{ $message }}</span> @enderror
    </div>

    {{-- Category: Current category selected hogi --}}
    <div class="form-group">
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id">
            <option value="">Select Category (Optional) </option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Price: Existing value load hogi --}}
    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" min="0.01"
            value="{{ old('price', $product->price) }}" required>
        @error('price') <span class="error-message">{{ $message }}</span> @enderror
    </div>

    {{-- Description: Existing value load hogi --}}
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
    </div>

    {{-- Image: Current image display hogi --}}
    <div class="form-group">
        <label for="image">Product Image:</label>

        @if ($product->image)
        <p>Current Image:</p>

        {{-- ✅ YEH CODE AB NAYA ROUTE USE KAREGA --}}
        @php
        // Extract sirf filename, path nahi
        $filename = basename($product->image);
        @endphp

        <img src="{{ route('storage.product.show', $filename) }}" alt="Current Image" width="100">
        <br>
        @endif

        <input type="file" id="image" name="image">
        @error('image') <span class="error-message">{{ $message }}</span> @enderror
    </div>
    <button type="submit">Update Product</button>
</form>

@endsection