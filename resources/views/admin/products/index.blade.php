@extends('admin.layouts.admin')

@section('content')
<x-admin.back />
<h1>Product Catalog</h1>

{{-- Assuming you have removed the old session('success') alert block --}}
{{-- Agar aapko Toast message dikhana hai to uski logic admin.layouts.admin mein hai --}}

{{-- 1. ADD NEW PRODUCT BUTTON --}}
<a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">
    ➕ Add New Product
</a>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                {{-- ✅ NEW: Stock Quantity Column --}}
                <th>Stock</th>
                <th>Category Name</th>
                {{-- ✅ NEW: Created At Column --}}
                <th>Created At</th>
                {{-- ✅ NEW: Updated At Column --}}
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>${{ number_format($product->price, 2) }}</td>

                {{-- ✅ NEW: Stock Quantity Display --}}
                <td>{{ $product->stock ?? 0 }}</td>

                {{-- ✅ DISPLAY CATEGORY NAME --}}
                <td>
                    @if ($product->category)
                    {{ $product->category->name }}
                    @else
                    N/A
                    @endif
                </td>

                {{-- ✅ NEW: Created At Display --}}
                <td>{{ $product->created_at->format('d-m-Y') }}</td>

                {{-- ✅ NEW: Updated At Display --}}
                <td>{{ $product->updated_at->format('d-m-Y') }}</td>

                <td>
                    {{-- 2. EDIT BUTTON --}}
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                        ✏️ Edit
                    </a>

                    {{-- 3. DELETE BUTTON --}}
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
</div>
@endsection