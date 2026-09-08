<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Outcome Management</h5>
            <div class="mt-2">
                <span class="text-white-50 small me-2">Subject:</span>
                <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-book me-1"></i>
                    <?= esc($subject_name) ?>
                    <small class="text-dark opacity-75">(ID: <?= esc($subject_id ?? '-') ?>)</small>
                </span>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <select id="gradeFilter" class="form-select rounded-pill" style="width: auto;">
                <option value="">All Grades</option>
                <?php
                $grades = array_unique(array_column($outcome, 'grade_name'));
                sort($grades);
                foreach ($grades as $g): if ($g): ?>
                    <option value="<?= esc($g) ?>"><?= esc($g) ?></option>
                <?php endif; endforeach; ?>
            </select>

            <a href="<?= base_url('outcome/create?subject_id='.$subject_id) ?>"
               class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Add outcome
            </a>
        </div>
    </div>

    <?php if (empty($outcome)): ?>
        <div class="text-center py-5">
            <i class="bi bi-folder-x display-4 text-white-50"></i>
            <p class="mt-3 text-muted">No outcomes found.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive"
             style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.1);">

            <table class="table glass-table align-middle mb-0" id="outcomeTable">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Subject</th>
                        <th>Objective</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Group outcomes by grade_name
                $grouped = [];
                foreach ($outcome as $u) {
                    $g = $u['grade_name'] ?? 'No Grade';
                    $grouped[$g][] = $u;
                }
                ksort($grouped);
                $firstRow = true;
                ?>
                <?php foreach ($grouped as $gradeName => $items): ?>
                    <tr class="grade-divider">
                        <td colspan="6" class="ps-3 bg-warning bg-opacity-100 fw-bold text-dark">
                            <i class="bi bi-collection me-1"></i> Grade: <?= esc($gradeName) ?>
                        </td>
                    </tr>
                    <?php foreach ($items as $u): ?>
                    <tr data-grade="<?= esc($u['grade_name'] ?? 'No Grade') ?>">
                        <td class="ps-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary">
                                <?= esc($u['id']) ?>
                            </span>
                        </td>

                        <td>
                            <div class="fw-bold text-dark">
                                <?= esc($u['outcome_name']) ?>
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-secondary"><?= esc($u['grade_name'] ?? '-') ?></span>
                        </td>

                        <td class="text-dark-50 small">
                            <?= esc($u['subject_name']) ?>
                        </td>

                         <td class="text-dark-50 small">
                            <a href="<?= base_url('objective?outcome_id='.$u['id'].'&subject_id='.$subject_id) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>

                        <td class="text-end pe-3">
                            <a href="<?= base_url('outcome/edit/'.$u['id']) ?>"
                               class="btn btn-sm btn-glass-edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="<?= base_url('outcome/delete/'.$u['id']) ?>"
                                  method="post"
                                  class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="subject_id" value="<?= esc($subject_id) ?>">
                               
                                <button type="submit"
                                        onclick="return confirm('Delete this outcome?')"
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        style="border-color:rgba(220,53,69,.3)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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
    document.getElementById('gradeFilter').addEventListener('change', function() {
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
</script>

<?= $this->endSection() ?>