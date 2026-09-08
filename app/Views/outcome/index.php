<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card card border-0 shadow-sm rounded-3 p-3">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-1 text-light">Outcome Management</h5>
            <div class="d-flex align-items-center gap-2 mt-1">
                <span class="text-light small">Subject:</span>
                <span class="badge bg-primary bg-opacity-10 text-warning border border-warning-subtle rounded px-2 py-1">
                    <i class="bi bi-book me-1"></i>
                    <?= esc($subject_name) ?>
                    <small class="ms-1 opacity-75">(ID: <?= esc($subject_id ?? '-') ?>)</small>
                </span>
            </div>
        </div>
        
        <div class="d-flex flex-wrap gap-2">
            <!-- Filter -->
            <div class="input-group input-group-sm shadow-sm rounded" style="width: auto;">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-funnel"></i>
                </span>
                <select id="gradeFilter" class="form-select form-select-sm border-start-0 shadow-none" style="min-width: 130px;">
                    <option value="">All Grades</option>
                    <?php
                    $grades = array_unique(array_column($outcome, 'grade_name'));
                    sort($grades);
                    foreach ($grades as $g): if ($g): ?>
                        <option value="<?= esc($g) ?>"><?= esc($g) ?></option>
                    <?php endif; endforeach; ?>
                </select>
            </div>

            <!-- Add Button -->
            <a href="<?= base_url('outcome/create?subject_id='.$subject_id) ?>"
               class="btn btn-sm btn-primary rounded px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Add Outcome
            </a>
        </div>
    </div>

    <!-- Content Section -->
    <?php if (empty($outcome)): ?>
        <!-- Empty State -->
        <div class="text-center py-4 rounded bg-light border border-dashed">
            <i class="bi bi-folder-x fs-2 text-muted mb-2 d-block"></i>
            <h6 class="fw-bold text-dark mb-1">No Outcomes Found</h6>
            <p class="text-muted small mb-3">No outcomes have been added for this subject.</p>
            <a href="<?= base_url('outcome/create?subject_id='.$subject_id) ?>" class="btn btn-sm btn-outline-primary rounded px-3">
                <i class="bi bi-plus-lg me-1"></i> Create Outcome
            </a>
        </div>
    <?php else: ?>
        <!-- Table Section -->
        <div class="table-responsive rounded shadow-sm border border-light">
            <!-- Ditambahkan table-sm untuk tampilan lebih compact -->
            <table class="table table-hover table-sm align-middle mb-0" id="outcomeTable">
                <thead>
                    <tr>
                        <th class="ps-3 py-2 text-secondary fw-semibold" style="width: 80px;">ID</th>
                        <th class="py-2 text-secondary fw-semibold">Outcome Name</th>
                        
                        
                        <th class="text-center py-2 text-secondary fw-semibold" style="width: 120px;">Objective</th>
                        <th class="text-end pe-3 py-2 text-secondary fw-semibold" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody class="small">
                <?php
                // Group outcomes by grade_name
                $grouped = [];
                foreach ($outcome as $u) {
                    $g = $u['grade_name'] ?? 'No Grade';
                    $grouped[$g][] = $u;
                }
                ksort($grouped);
                ?>
                
                <?php foreach ($grouped as $gradeName => $items): ?>
                    <!-- Divider Grade -->
                   <!-- Grade Divider -->
                <tr class="grade-divider" data-grade="<?= esc($gradeName) ?>">
                    <td colspan="6" class="ps-3 py-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="grade-indicator"></span>
                            <span class="grade-title">
                                <?= esc($gradeName) ?>
                            </span>
                            <span class="grade-count">
                                <?= count($items) ?> outcome<?= count($items) > 1 ? 's' : '' ?>
                            </span>
                        </div>
                    </td>
                </tr>
                    
                    <?php foreach ($items as $u): ?>
                    <tr data-grade="<?= esc($u['grade_name'] ?? 'No Grade') ?>">
                        <td class="ps-3 text-muted">
                            #<?= esc($u['id']) ?>
                        </td>

                        <td class="fw-medium text-dark">
                            <?= esc($u['outcome_name']) ?>
                        </td>

                    

                        <!-- Objective Button -->
                        <td class="text-center">
                            <a href="<?= base_url('objective?outcome_id='.$u['id'].'&subject_id='.$subject_id) ?>"
                               class="btn btn-sm btn-outline-primary py-0 px-2"
                               style="font-size: 0.8rem;"
                               data-bs-toggle="tooltip" 
                               title="Manage Objectives">
                                <i class="bi bi-bullseye me-1"></i> Obj
                            </a>
                        </td>

                        <!-- Actions -->
                        <td class="text-end pe-3">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="<?= base_url('outcome/edit/'.$u['id']) ?>"
                                   class="btn btn-sm btn-light border py-0 px-2 text-primary"
                                   data-bs-toggle="tooltip" 
                                   title="Edit">
                                    <i class="bi bi-pencil-square" style="font-size: 0.85rem;"></i>
                                </a>

                                <form action="<?= base_url('outcome/delete/'.$u['id']) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="subject_id" value="<?= esc($subject_id) ?>">
                                    <button type="submit"
                                            onclick="return confirm('Delete this outcome?')"
                                            class="btn btn-sm btn-light border py-0 px-2 text-danger"
                                            data-bs-toggle="tooltip" 
                                            title="Delete">
                                        <i class="bi bi-trash3" style="font-size: 0.85rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Filter Logic
        const gradeFilter = document.getElementById('gradeFilter');
        if (gradeFilter) {
            gradeFilter.addEventListener('change', function() {
                const grade = this.value;
                const rows = document.querySelectorAll('#outcomeTable tbody tr');
                
                rows.forEach(row => {
                    if (grade === '' || row.getAttribute('data-grade') === grade) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>

<?= $this->endSection() ?>