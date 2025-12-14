@extends('admin.layouts.admin')

@section('content')

<style>
    /* ===== NEW UPGRADED LAYOUT + CLEAN UI ===== */
    :root {
        --primary: #db4444;
        --primary-light: #e05a5a;
        --border: #dadce0;
    }

    /* Header */
    .modern-header {
        padding: 22px 28px;
        border-radius: 12px;
        background: white;
        border: 1px solid var(--border);
        margin-bottom: 26px;
    }

    .modern-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
    }

    .modern-header p {
        margin-top: 6px;
        color: #666;
        font-size: 14px;
    }

    /* Outer Card */
    .form-wrapper {
        margin: 0 auto;
        display: grid;
        gap: 24px;
    }

    /* Section Card */
    .section-card {
        background: white;
        padding: 24px;
        border-radius: 14px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .section-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 18px;
        color: #333;
        padding-left: 4px;
        border-left: 3px solid var(--primary);
    }

    /* Grid */
    /* .section-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
    } */
    .section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        /* Each input 100% width */
        gap: 18px;
    }


    /* Floating Fields */
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
        transition: all .2s ease;
    }

    .modern-textarea {
        height: 110px;
    }

    /* Remove black outline from all inputs */
    .modern-input:focus,
    .modern-select:focus,
    .modern-textarea:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(255, 59, 59, 0.20) !important;
    }

    /* Number input removes black default ring */
    input[type="number"] {
        outline: none !important;
    }

    input[type="number"]:focus {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(255, 59, 59, 0.20) !important;
        border-color: #ff3b3b !important;
    }

    /* Remove default chrome black inset border */
    input[type="number"] {
        -webkit-appearance: none;
        -moz-appearance: textfield;
        appearance: none;
    }

    /* Remove arrow dark ring on focus */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        margin: 0;
    }



    .modern-label {
        position: absolute;
        top: 15px;
        left: 15px;
        font-size: 14px;
        color: #777;
        background: #fff;
        padding: 0 6px;
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

    /* Upload Field Row */
    .media-row {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    /* Submit Button */
    .modern-btn {
        width: 100%;
        padding: 15px;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: white;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: .2s ease;
    }

    .modern-btn:hover {
        background: var(--primary-light);
    }

    /* Shopify-style Image Upload */
    .upload-container {
        border: 2px dashed #cfd4dc;
        border-radius: 12px;
        padding: 32px;
        text-align: center;
        background: #fbfcfe;
        width: 100%;
        transition: 0.25s ease;
        cursor: pointer;
    }

    .upload-container:hover {
        background: #e05a5a17;
        border-color: #e05a5a;
    }

    .upload-icon {
        font-size: 32px;
        color: #5a6a85;
        margin-bottom: 8px;
    }

    .upload-text {
        color: #5a6a85;
        font-size: 15px;
    }

    .upload-browse {
        color: #3b82f6;
        text-decoration: underline;
        cursor: pointer;
    }

    .image-preview-box {
        display: none;
        justify-content: center;
        margin-top: 18px;
    }

    .image-preview-box img {
        width: auto;
        height: 160px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #d6d6d6;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>

<x-admin.back />

<div class="modern-header">
    <h2>➕ Add New Product</h2>
    <p>Fill the details below to add a product to your catalog.</p>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-wrapper">

        <!-- SECTION 1 -->
        <div class="section-card">
            <div class="section-title">Basic Information</div>

            <div class="section-grid">

                <div class="form-group-modern">
                    <input type="text" name="name" class="modern-input" placeholder=" " required>
                    <label class="modern-label">Product Name</label>
                </div>

                <div class="form-group-modern">
                    <select name="category_id" class="modern-select">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label class="modern-label">Category</label>
                </div>

                <div class="form-group-modern">
                    <input type="number" name="price" class="modern-input" placeholder=" " required>
                    <label class="modern-label">Price (₹)</label>
                </div>

                <div class="form-group-modern">
                    <input type="number" name="stock" class="modern-input" placeholder=" " required>
                    <label class="modern-label">Stock Quantity</label>
                </div>

                <div class="form-group-modern" style="grid-column: 1 / -1;">
                    <textarea name="manufacturer" class="modern-textarea" placeholder=" "></textarea>
                    <label class="modern-label">Manufacturer</label>
                </div>



            </div>
        </div>

        <!-- SECTION 2 -->
        <div class="section-card">
            <div class="section-title">Inventory & Media</div>

            <div class="form-group-modern">
                <textarea name="description" class="modern-textarea" placeholder=" "></textarea>
                <label class="modern-label">Description</label>
            </div>

            <div class="section-title" style="margin-top:10px;">Product Image</div>

            <label for="image" class="upload-container">
                <div class="upload-icon">⤴️</div>
                <div class="upload-text">
                    Drag your image here <br>
                    or, <span class="upload-browse">Browse</span>
                </div>
            </label>

            <input type="file" id="image" name="image" style="display:none;">

            <div id="previewBox" class="image-preview-box">
                <img id="previewImg" />
            </div>

        </div>

        <!-- BUTTON -->
        <button class="modern-btn">Add Product</button>

    </div>
</form>
<script>
    document.getElementById("image").addEventListener("change", function(e) {
        let file = e.target.files[0];
        if (!file) return;

        let previewImg = document.getElementById("previewImg");
        let previewBox = document.getElementById("previewBox");

        previewImg.src = URL.createObjectURL(file);
        previewBox.style.display = "flex";
    });
</script>


@endsection