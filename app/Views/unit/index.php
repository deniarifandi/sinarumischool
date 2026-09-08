<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card card border-0 shadow-sm rounded-3 p-3">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-1 text-light">Unit Management</h5>

            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                <span class="text-light small">Subject:</span>

                <span class="badge bg-primary bg-opacity-10 text-warning border border-warning-subtle rounded px-2 py-1">
                                    <i class="bi bi-book me-1"></i>
                                    <?= esc($subjectName ?? $subject_name ?? '-') ?>
                                    <small class="ms-1 opacity-75">
                                        (ID: <?= esc($subjectId ?? '-') ?>)
                                    </small>
                                </span>

                <?php if (!empty($gradeId)): ?>
                    <span class="badge bg-secondary bg-opacity-10 text-light border border-secondary-subtle rounded px-2 py-1">
                        Grade ID: <?= esc($gradeId) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <!-- Grade Filter -->
            <form method="get" class="m-0">
                <input type="hidden"
                       name="subject_id"
                       value="<?= esc($subjectId) ?>">

                <?php if (!empty($division_id) && $division_id !== '-'): ?>
                    <input type="hidden"
                           name="division_id"
                           value="<?= esc($division_id) ?>">
                <?php endif; ?>

                <div class="input-group input-group-sm shadow-sm rounded">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="bi bi-funnel"></i>
                    </span>

                    <select name="grade_id"
                            class="form-select form-select-sm border-start-0 shadow-none"
                            style="min-width: 130px;"
                            onchange="this.form.submit()">

                        <option value="">All Grades</option>

                        <?php foreach ($grades as $g): ?>
                            <option value="<?= esc($g['id']) ?>"
                                <?= ($gradeId == $g['id']) ? 'selected' : '' ?>>
                                <?= esc($g['grade_name']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </form>

            <!-- Add Button -->
            <a href="<?= base_url('unit/create?subject_id='.$subjectId) ?>"
               class="btn btn-sm btn-primary rounded px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                Add Unit
            </a>

        </div>
    </div>


    <!-- Content Section -->
    <?php if (empty($units)): ?>

        <!-- Empty State -->
        <div class="text-center py-4 rounded bg-light border border-dashed">
            <i class="bi bi-folder-x fs-2 text-muted mb-2 d-block"></i>

            <h6 class="fw-bold text-dark mb-1">
                No Units Found
            </h6>

            <p class="text-muted small mb-3">
                No units have been added for this subject.
            </p>

            <a href="<?= base_url('unit/create?subject_id='.$subjectId) ?>"
               class="btn btn-sm btn-outline-primary rounded px-3">
                <i class="bi bi-plus-lg me-1"></i>
                Create Unit
            </a>
        </div>

    <?php else: ?>

        <!-- Table Section -->
        <div class="table-responsive rounded shadow-sm border border-light">

            <table class="table table-hover table-sm align-middle mb-0"
                   id="unitTable">

                <thead>
                    <tr>
                        <th class="ps-3 py-2 text-secondary fw-semibold"
                            style="width: 80px;">
                            ID
                        </th>

                        <th class="py-2 text-secondary fw-semibold">
                            Unit Name
                        </th>

                        <th class="py-2 text-secondary fw-semibold"
                            style="width: 120px;">
                            Term
                        </th>

                        <th class="text-center py-2 text-secondary fw-semibold"
                            style="width: 120px;">
                            Subunit
                        </th>

                        <th class="text-end pe-3 py-2 text-secondary fw-semibold"
                            style="width: 100px;">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="small">

                    <?php
                    // Group units by grade
                    $grouped = [];

                    foreach ($units as $u) {
                        $g = $u['grade_name'] ?? 'No Grade';
                        $grouped[$g][] = $u;
                    }

                    ksort($grouped);
                    ?>

                    <?php foreach ($grouped as $gradeName => $items): ?>

                        <!-- Grade Divider -->
                        <tr class="grade-divider"
                            data-grade="<?= esc($gradeName) ?>">

                            <td colspan="5" class="ps-3 py-2">

                                <div class="d-flex align-items-center gap-2">

                                    <span class="grade-indicator"></span>

                                    <span class="grade-title">
                                        <?= esc($gradeName) ?>
                                    </span>

                                    <span class="grade-count">
                                        <?= count($items) ?>
                                        unit<?= count($items) > 1 ? 's' : '' ?>
                                    </span>

                                </div>

                            </td>
                        </tr>


                        <?php foreach ($items as $u): ?>

                            <tr data-grade="<?= esc($u['grade_name'] ?? 'No Grade') ?>">

                                <!-- ID -->
                                <td class="ps-3 text-muted">
                                    #<?= esc($u['id']) ?>
                                </td>


                                <!-- Unit Name -->
                                <td class="fw-medium text-dark">
                                    <?= esc($u['name']) ?>
                                </td>


                                <!-- Term -->
                                <td class="text-muted">
                                    <?= esc($u['term_name'] ?? '-') ?>
                                </td>


                                <!-- Subunit -->
                                <td class="text-center">

                                    <a href="<?= base_url('subunit?unit_id='.$u['id']) ?>"
                                       class="btn btn-sm btn-outline-primary py-0 px-2"
                                       style="font-size: 0.8rem;"
                                       data-bs-toggle="tooltip"
                                       title="Manage Subunits">

                                        <i class="bi bi-collection me-1"></i>
                                        Sub
                                    </a>

                                </td>


                                <!-- Actions -->
                                <td class="text-end pe-3">

                                    <div class="d-flex gap-1 justify-content-end">

                                        <!-- Edit -->
                                        <a href="<?= base_url('unit/edit/'.$u['id']) ?>"
                                           class="btn btn-sm btn-light border py-0 px-2 text-primary"
                                           data-bs-toggle="tooltip"
                                           title="Edit">

                                            <i class="bi bi-pencil-square"
                                               style="font-size: 0.85rem;"></i>

                                        </a>


                                        <!-- Delete -->
                                        <form action="<?= base_url('unit/delete/'.$u['id']) ?>"
                                              method="post"
                                              class="d-inline">

                                            <?= csrf_field() ?>

                                            <input type="hidden"
                                                   name="subject_id"
                                                   value="<?= esc($subjectId) ?>">

                                            <button type="submit"
                                                    onclick="return confirm('Delete this unit?')"
                                                    class="btn btn-sm btn-light border py-0 px-2 text-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Delete">

                                                <i class="bi bi-trash3"
                                                   style="font-size: 0.85rem;"></i>

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
document.addEventListener("DOMContentLoaded", function () {

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

});
</script>

<?= $this->endSection() ?>