{{-- resources/views/admin/categories/create.blade.php --}}

@extends('admin.layouts.admin') {{-- Tumhara master admin layout --}}

@section('content')
    <h1>Add New Category</h1>
    
    {{-- Success/Error Messages yahaan aayenge --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="name">Category Name (Required):</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">Description (Optional):</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Category</button>
    </form>
@endsection