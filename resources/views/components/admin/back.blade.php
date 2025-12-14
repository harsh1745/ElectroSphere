<div class="d-flex gap-2 mb-3 mt-3">

    <!-- Go Back (previous page) -->
    <a href="{{ url()->previous() }}"
        class="btn btn-outline-secondary btn-sm shadow-sm fw-semibold"
        style="border-radius: 6px;">
        <i class="fas fa-arrow-left me-1"></i> Go Back
    </a>

    <!-- Back to Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
        class="btn btn-danger btn-sm shadow-sm fw-semibold"
        style="border-radius: 6px; background:#DB4444; border:none;">
        <i class="fas fa-home me-1"></i> Back to Dashboard
    </a>

</div>