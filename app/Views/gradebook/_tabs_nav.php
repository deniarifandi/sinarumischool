<style>
    /* Membuat tab tidak aktif terlihat lebih "dalam/tenggelam" */
    .custom-deep-tabs .nav-link {
        background-color: #f1f3f5; /* Abu-abu terang */
        color: #6c757d; /* Warna teks pudar */
        font-weight: 500;
        border: 1px solid #dee2e6;
        border-bottom: none;
        opacity: 0.7;
        transition: all 0.3s ease;
    }
    
    /* Efek saat mouse diarahkan ke tab tidak aktif */
    .custom-deep-tabs .nav-link:hover {
        opacity: 0.9;
        background-color: #e9ecef;
    }

    /* Membuat tab aktif sangat menonjol (highlighted) */
    .custom-deep-tabs .nav-link.active {
        background-color: #ffffff;
        color: #0d6efd; /* Warna biru primary */
        font-weight: 700; /* Bold */
        opacity: 1;
        box-shadow: 0 -4px 6px -4px rgba(0,0,0,0.15); /* Bayangan atas */
        border-color: #dee2e6 #dee2e6 #ffffff; /* Menyatu dengan konten bawah */
    }
</style>

<!-- Tambahkan class 'custom-deep-tabs' pada <ul> -->
<ul class="nav nav-tabs custom-deep-tabs " id="gbTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button
            class="nav-link active"
            id="tab-ct-tab"
            data-bs-toggle="tab"
            data-bs-target="#tab-ct"
            type="button"
            role="tab"
            aria-controls="tab-ct"
            aria-selected="true"
        >
            <i class="bi bi-file-earmark-text me-1"></i>
            Chapter Test (CT)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button
            class="nav-link"
            id="tab-objective-tab"
            data-bs-toggle="tab"
            data-bs-target="#tab-objective"
            type="button"
            role="tab"
            aria-controls="tab-objective"
            aria-selected="false"
        >
            <i class="bi bi-list-check me-1"></i>
            Objective-Based
        </button>
    </li>
</ul>