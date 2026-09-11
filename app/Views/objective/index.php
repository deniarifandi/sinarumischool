<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card card border-0 shadow-sm rounded-3 p-3">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">

        <div>
            <h5 class="fw-bold mb-1 text-light">Objective Management</h5>

            <?php
            $outcome_id = esc($_GET['outcome_id'] ?? '-');
            $subject_id = esc($_GET['subject_id'] ?? '-');
            ?>

            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">

                <?php if (!empty($subject_name)): ?>
                    <span class="text-light small">Subject:</span>

                    <span class="badge bg-primary bg-opacity-10 text-warning border border-warning-subtle rounded px-2 py-1">
                        <i class="bi bi-book me-1"></i>
                        <?= esc($subject_name) ?>
                        <small class="ms-1 opacity-75">
                            (ID: <?= esc($subject_id) ?>)
                        </small>
                    </span>
                <?php endif; ?>

                <?php if (!empty($outcome_name)): ?>
                    <span class="text-light small">Term Code:</span>

                    <span class=" bg-opacity-10 text-info rounded px-2 py-1">
                        <i class="bi bi-bullseye me-1"></i>
                        <?= esc($outcome_name) ?>
                        <small class="ms-1 opacity-75">
                            (ID: <?= esc($outcome_id) ?>)
                        </small>
                    </span>
                <?php endif; ?>

            </div>
        </div>


        <div class="d-flex flex-wrap gap-2">

            <!-- Back -->
            <a href="<?= base_url('outcome?subject_id='.$subject_id) ?>"
               class="btn btn-sm btn-outline-secondary rounded px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <!-- Add Objective -->
            <a href="<?= base_url('objective/create?outcome_id='.$outcome_id.'&subject_id='.$subject_id) ?>"
               class="btn btn-sm btn-primary rounded px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                Add Objective
            </a>

        </div>
    </div>


    <!-- Content Section -->
    <?php if (empty($objective)): ?>

        <!-- Empty State -->
        <div class="text-center py-4 rounded bg-light border border-dashed">

            <i class="bi bi-folder-x fs-2 text-muted mb-2 d-block"></i>

            <h6 class="fw-bold text-dark mb-1">
                No Objectives Found
            </h6>

            <p class="text-muted small mb-3">
                No objectives have been added to this term code.
            </p>

            <a href="<?= base_url('objective/create?outcome_id='.$outcome_id.'&subject_id='.$subject_id) ?>"
               class="btn btn-sm btn-outline-primary rounded px-3">
                <i class="bi bi-plus-lg me-1"></i>
                Create Objective
            </a>

        </div>

    <?php else: ?>

        <!-- Table Section -->
        <div class="table-responsive rounded shadow-sm border border-light">

            <table class="table table-hover table-sm align-middle mb-0"
                   id="objectiveTable">

                <thead>
                    <tr>

                        <th class="ps-3 py-2 text-secondary fw-semibold"
                            style="width: 80px;">
                            ID
                        </th>

                        <th class="py-2 text-secondary fw-semibold">
                            Objective
                        </th>

                        <th class="py-2 text-secondary fw-semibold"
                            style="width: 120px;">
                            Term
                        </th>

                        <th class="text-end pe-3 py-2 text-secondary fw-semibold"
                            style="width: 100px;">
                            Actions
                        </th>

                    </tr>
                </thead>

                <tbody class="small">

                <?php foreach ($objective as $u): ?>

                    <tr>

                        <!-- ID -->
                        <td class="ps-3 text-muted">
                            #<?= esc($u['id']) ?>
                        </td>


                        <!-- Objective -->
                        <td class="fw-medium text-dark">
                            <?= esc($u['objective_name']) ?>
                        </td>


                        <!-- Term -->
                        <td>

                            <?php if (!empty($u['term_id'])): ?>

                                <span class="badge bg-info bg-opacity-25 text-dark">
                                    Term <?= esc($u['term_id']) ?>
                                </span>

                            <?php else: ?>

                                <span class="text-muted">
                                    -
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- Actions -->
                        <td class="text-end pe-3">

                            <div class="d-flex gap-1 justify-content-end">

                                <!-- Edit -->
                                <a href="<?= base_url('objective/edit/'.$u['id']) ?>"
                                   class="btn btn-sm btn-light border py-0 px-2 text-primary"
                                   data-bs-toggle="tooltip"
                                   title="Edit">

                                    <i class="bi bi-pencil-square"
                                       style="font-size: 0.85rem;"></i>

                                </a>


                                <!-- Delete -->
                                <form action="<?= base_url('objective/delete/'.$u['id']) ?>"
                                      method="post"
                                      class="d-inline">

                                    <?= csrf_field() ?>

                                    <input type="hidden"
                                           name="outcome_id"
                                           value="<?= esc($outcome_id) ?>">

                                    <button type="submit"
                                            onclick="return confirm('Delete this objective?')"
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