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
    padding: 18px 22px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid var(--border);
    margin-bottom: 26px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    max-width: 700px;
    margin: 0 auto;
}

/* Floating Inputs */
.form-group-modern {
    position: relative;
    margin-bottom: 22px;
}

.modern-input,
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

.modern-input:focus,
.modern-textarea:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 2px rgba(219,68,68,0.25);
}

/* Floating Label */
.modern-label {
    position: absolute;
    top: 16px;
    left: 15px;
    font-size: 14px;
    background: #fff;
    padding: 0 6px;
    color: #777;
    transition: .18s ease;
    pointer-events: none;
}

.modern-input:focus + .modern-label,
.modern-input:not(:placeholder-shown) + .modern-label,
.modern-textarea:focus + .modern-label,
.modern-textarea:not(:placeholder-shown) + .modern-label {
    top: -8px;
    left: 10px;
    font-size: 12px;
    color: var(--primary);
}

/* Submit Button */
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
</style>

<x-admin.back />

<div class="modern-header">
    <h2>➕ Add New Category</h2>
</div>

<div class="section-card">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <!-- Category Name -->
        <div class="form-group-modern">
            <input type="text" name="name" class="modern-input" placeholder=" " required>
            <label class="modern-label">Category Name (Required)</label>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div class="form-group-modern">
            <textarea name="description" class="modern-textarea" placeholder=" "></textarea>
            <label class="modern-label">Description (Optional)</label>
        </div>

        <button type="submit" class="modern-btn">Save Category</button>
    </form>

</div>

@endsection
