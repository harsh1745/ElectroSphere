@extends('admin.layouts.admin')

@section('content')

<style>
    :root {
        --primary: #db4444;
        --primary-light: #e05a5a;
        --border: #dadce0;
    }

    /* Header */
    .modern-header {
        padding: 22px 28px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid var(--border);
        margin-bottom: 26px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .modern-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }

    /* Card */
    .section-card {
        background: white;
        padding: 24px;
        border-radius: 14px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        margin: 0 auto;
    }

    /* Grid */
    .section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        gap: 18px;
    }

    /* Floating Inputs */
    .form-group-modern {
        position: relative;
    }

    .modern-input,
    .modern-select,
    .modern-textarea {
        width: 100%;
        padding: 15px 14px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        transition: .25s ease;
    }

    .modern-textarea {
        height: 110px;
    }

    /* Focus Border Red */
    .modern-input:focus,
    .modern-select:focus,
    .modern-textarea:focus {
        border-color: var(--primary) !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(219, 68, 68, 0.25) !important;
    }

    .modern-label {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #fff;
        padding: 0 6px;
        font-size: 14px;
        color: #777;
        transition: .18s ease;
        pointer-events: none;
    }

    /* Floating animation */
    .modern-input:focus+.modern-label,
    .modern-input:not(:placeholder-shown)+.modern-label,
    .modern-select:focus+.modern-label,
    .modern-select:not([value=""])+.modern-label,
    .modern-textarea:focus+.modern-label,
    .modern-textarea:not(:placeholder-shown)+.modern-label {
        top: -9px;
        left: 10px;
        font-size: 12px;
        color: var(--primary);
    }

    /* Button */
    .modern-btn {
        width: 100%;
        padding: 15px;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #fff;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: .2s;
    }

    .modern-btn:hover {
        background: var(--primary-light);
    }

    /* Shopify Image Upload */
    .upload-container {
        border: 2px dashed #cfd4dc;
        border-radius: 12px;
        padding: 32px;
        text-align: center;
        background: #fbfcfe;
        width: 100%;
        transition: .25s ease;
        cursor: pointer;
    }

    .upload-container:hover {
        background: #fff5f5;
        border-color: #db4444;
    }

    .upload-icon {
        font-size: 32px;
        color: #db4444;
    }

    .upload-browse {
        color: var(--primary);
        text-decoration: underline;
        cursor: pointer;
    }

    .image-preview-box {
        display: none;
        justify-content: center;
        margin-top: 18px;
    }

    .image-preview-box img {
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #d6d6d6;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<x-admin.back />

<div class="modern-header">
    <h2>Edit Product: {{ $product->name }}</h2>
</div>

<div class="section-card">

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="section-grid">

            <!-- Product Name -->
            <div class="form-group-modern">
                <input type="text" name="name" class="modern-input" placeholder=" "
                    value="{{ old('name', $product->name) }}" required>
                <label class="modern-label">Product Name</label>
            </div>

            <!-- Category -->
            <div class="form-group-modern">
                <select name="category_id" class="modern-select">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                <label class="modern-label">Category</label>
            </div>

            <!-- Price -->
            <div class="form-group-modern">
                <input type="number" name="price" class="modern-input" placeholder=" "
                    value="{{ old('price', $product->price) }}" required>
                <label class="modern-label">Price (₹)</label>
            </div>

            <!-- Stock -->
            <div class="form-group-modern">
                <input type="number" name="stock" class="modern-input" placeholder=" "
                    value="{{ old('stock', $product->stock) }}" required>
                <label class="modern-label">Stock Quantity</label>
            </div>

            <!-- Manufacturer (Full Width) -->
            <div class="form-group-modern" style="grid-column: 1 / -1;">
                <textarea name="manufacturer" class="modern-textarea" placeholder=" ">{{ old('manufacturer', $product->manufacturer) }}</textarea>
                <label class="modern-label">Manufacturer</label>
            </div>

        </div>

        <!-- Description Full Width -->
        <div class="form-group-modern" style="margin-top: 20px;">
            <textarea name="description" class="modern-textarea" placeholder=" ">{{ old('description', $product->description) }}</textarea>
            <label class="modern-label">Description</label>
        </div>

        <!-- Product Image -->
        <div class="section-title">Product Image</div>

        <p><strong>Current Image:</strong></p>
        @php $filename = basename($product->image); @endphp
        <img src="{{ route('storage.product.show', $filename) }}" width="300" style="border-radius:10px; margin-bottom:10px;">

        <!-- Upload New -->
        <label for="image" class="upload-container">
            <div class="upload-icon">⤴️</div>
            <div class="upload-text">
                Drag your new image here <br>
                or, <span class="upload-browse">Browse</span>
            </div>
        </label>

        <input type="file" id="image" name="image" style="display:none;">

        <div id="previewBox" class="image-preview-box">
            <img id="previewImg" />
        </div>

        <button type="submit" class="modern-btn" style="margin-top:20px;">Update Product</button>

    </form>

</div>

<script>
    document.getElementById("image").addEventListener("change", function(e) {
        let file = e.target.files[0];
        if (!file) return;

        let previewBox = document.getElementById("previewBox");
        let previewImg = document.getElementById("previewImg");

        previewImg.src = URL.createObjectURL(file);
        previewBox.style.display = "flex";
    });
</script>

@endsection