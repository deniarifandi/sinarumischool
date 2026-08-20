<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    /* Main Card Container */
    .main-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    /* Student Card */
    .student-card {
        background: #ffffff;
        border: 1px solid #eaedf1;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer; 
        text-decoration: none !important; 
    }
    
    .student-card:hover {
        transform: translateY(-5px);
        border-color: #ffc107;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    /* Fake Button Hover Effect */
    .fake-btn {
        transition: all 0.3s ease;
    }
    .student-card:hover .fake-btn {
        background-color: #ffc107;
        color: #fff !important;
    }

    /* Alphabet Filter */
    .alpha-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    
    .alpha-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #6c757d;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
    }
    
    .alpha-btn:hover {
        background: #e2e6ea;
        color: #212529;
    }
    
    .alpha-btn.active {
        background: #ffc107; 
        color: #fff;
        border-color: #ffc107;
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
    }

    /* Custom Search Input */
    .search-input-group .input-group-text {
        background: transparent;
        border-right: none;
    }
    .search-input-group .form-control {
        border-left: none;
        padding-left: 0;
    }
    .search-input-group .form-control:focus {
        border-color: #ced4da;
        box-shadow: none;
    }
    .search-input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
        border-radius: 0.375rem;
    }
</style>

<div class="container-fluid px-0 py-3">
    <div class="main-card p-4 mb-4">
        
        <!-- Header & Search -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <h4 class="text-dark fw-bold mb-0">
                    <i class="bi bi-people-fill text-danger me-2"></i>Student Directory
                </h4>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-sm-lg search-input-group">
                    <span class="input-group-text border-secondary-subtle text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchStudent" class="form-control border-secondary-subtle text-dark"
                           placeholder="Search by student name...">
                </div>
            </div>
        </div>

        <!-- Alphabet Filter -->
        <div class="mb-4">
            <div class="text-muted small mb-2 fw-semibold text-uppercase tracking-wide">Filter by Initial</div>
            <div class="alpha-filter" id="alphabetFilter">
                <button class="alpha-btn active" data-alpha="ALL">All</button>
                <?php foreach (range('A', 'Z') as $letter): ?>
                    <button class="alpha-btn" data-alpha="<?= $letter ?>"><?= $letter ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <hr class="border-secondary-subtle mb-4">

        <!-- Student Grid -->
        <div class="row g-4" id="studentGrid">
            <?php foreach ($students as $student): ?>
                <div class="col-12 col-md-6 col-xl-4 student-item"
                     data-name="<?= esc(strtolower($student['name'])) ?>">
                    
                    <a href="<?= base_url('student/dashboard/'.$student['id']) ?>" 
                       class="student-card p-3 d-flex align-items-center h-100 d-block bg-light">
                        
                        <!-- Avatar / Initial -->
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3 fw-bold fs-5"
                             style="width:50px; height:50px;">
                            <?= esc(strtoupper(substr($student['name'], 0, 1))) ?>
                        </div>
                        
                        <!-- Student Info -->
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="text-dark fw-bold mb-1 text-truncate" title="<?= esc($student['name']) ?>">
                                <?= esc($student['name']) ?>
                            </h6>
                            <div class="text-muted small d-flex align-items-center">
                                <i class="bi bi-mortarboard me-1"></i>
                                <?= esc($student['class_name'] ?? 'No class assigned') ?>
                            </div>
                        </div>
                        
                        <!-- Indicator -->
                        <div class="fake-btn btn btn-sm border-danger text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 ms-2" style="width: 32px; height: 32px; padding: 0;">
                            <i class="bi bi-chevron-right fw-bold"></i>
                        </div>
                        
                    </a>

                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-5" style="display: none;">
            <i class="bi bi-search text-muted opacity-25" style="font-size: 4rem;"></i>
            <h5 class="fw-bold text-dark mt-3">No students found</h5>
            <p class="text-muted">We couldn't find any student matching your search or filter criteria.</p>
            <button class="btn btn-sm btn-outline-secondary rounded-pill mt-2" onclick="resetFilters()">Clear Filters</button>
        </div>

    </div>
</div>

<script>
    let searchQuery = '';
    let alphaFilter = 'ALL';

    const searchInput  = document.getElementById('searchStudent');
    const alphaButtons = document.querySelectorAll('.alpha-btn');
    const studentItems = document.querySelectorAll('.student-item');
    const emptyState   = document.getElementById('emptyState');

    function applyFilters() {
        let visibleCount = 0;
        
        studentItems.forEach(el => {
            const name = el.dataset.name;
            const matchesSearch = name.includes(searchQuery);
            const matchesAlpha = (alphaFilter === 'ALL') || name.startsWith(alphaFilter.toLowerCase());

            if (matchesSearch && matchesAlpha) {
                el.style.display = '';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });

        emptyState.style.display = (visibleCount === 0) ? 'block' : 'none';
    }

    searchInput.addEventListener('input', function () {
        searchQuery = this.value.toLowerCase();
        applyFilters();
    });

    alphaButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            alphaButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            alphaFilter = this.dataset.alpha;
            applyFilters();
        });
    });

    function resetFilters() {
        searchInput.value = '';
        searchQuery = '';
        
        alphaButtons.forEach(b => b.classList.remove('active'));
        document.querySelector('[data-alpha="ALL"]').classList.add('active');
        alphaFilter = 'ALL';
        
        applyFilters();
    }
</script>

<?= $this->endSection() ?>