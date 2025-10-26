@extends('admin.layouts.admin')

@section('content')

<h1>Add New Product</h1>
<p>Fill in the details to add a new product to the catalog.</p>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name"
            class="webflow-input" value="{{ old('name') }}" required>
        @error('name') <span class="error-message">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="category_id">Categ ory:</label>
        <select id="category_id" name="category_id" class="webflow-select">
            <option value="" {{ old('category_id') == '' ? 'selected' : '' }}>Select Category (Optional) </option>
            {{-- $categories data Controller se aayega --}}
            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" min="0.01"
            class="webflow-input" value="{{ old('price') }}" required>
        @error('price') <span class="error-message">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="description">Description:</label>
        <textarea id="description" name="description" class="webflow-textarea">{{ old('description') }}</textarea>
    </div>

    <div class="form-group">
        <label for="image">Product Image:</label>
        <input type="file" id="image" name="image" class="webflow-file-input">
        @error('image') <span class="error-message">{{ $message }}</span> @enderror
    </div>

    <button type="submit" id="submit-product" class="webflow-submit-button">Add Product</button>
</form>

@endsection