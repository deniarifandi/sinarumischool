<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card card border-0 shadow-sm rounded-3 p-3">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-1 text-light">Subunit Management</h5>

            <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
                <span class="text-light small">Unit:</span>

                <span class="badge bg-primary bg-opacity-10 text-warning border border-warning-subtle rounded px-2 py-1">
                    <i class="bi bi-collection me-1"></i>
                    <?= esc($subunits[0]['name'] ?? 'Unknown Unit') ?>
                    <small class="ms-1 opacity-75">
                        (ID: <?= esc($unit_id ?? '-') ?>)
                    </small>
                </span>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <!-- Add Button -->
            <a href="<?= base_url('subunit/create?unit_id='.$unit_id) ?>"
               class="btn btn-sm btn-primary rounded px-3 shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                Add Subunit
            </a>

        </div>
    </div>


    <!-- Content Section -->
    <?php if (empty($subunits)): ?>

        <!-- Empty State -->
        <div class="text-center py-4 rounded bg-light border border-dashed">
            <i class="bi bi-folder-x fs-2 text-muted mb-2 d-block"></i>

            <h6 class="fw-bold text-dark mb-1">
                No Subunits Found
            </h6>

            <p class="text-muted small mb-3">
                No subunits have been added to this unit.
            </p>

            <a href="<?= base_url('subunit/create?unit_id='.$unit_id) ?>"
               class="btn btn-sm btn-outline-primary rounded px-3">
                <i class="bi bi-plus-lg me-1"></i>
                Create Subunit
            </a>
        </div>

    <?php else: ?>

        <!-- Table Section -->
        <div class="table-responsive rounded shadow-sm border border-light">

            <table class="table table-hover table-sm align-middle mb-0"
                   id="subunitTable">

                <thead>
                    <tr>
                        <th class="ps-3 py-2 text-secondary fw-semibold"
                            style="width: 80px;">
                            ID
                        </th>

                        <th class="py-2 text-secondary fw-semibold">
                            Subunit Name
                        </th>

                        <th class="text-end pe-3 py-2 text-secondary fw-semibold"
                            style="width: 100px;">
                            Actions
                        </th>
                    </tr>
                </thead>

                <tbody class="small">

                    <?php foreach ($subunits as $s): ?>

                        <tr>

                            <!-- ID -->
                            <td class="ps-3 text-muted">
                                #<?= esc($s['id']) ?>
                            </td>


                            <!-- Subunit Name -->
                            <td class="fw-medium text-dark">
                                <?= esc($s['subunit_name']) ?>
                            </td>


                            <!-- Actions -->
                            <td class="text-end pe-3">

                                <div class="d-flex gap-1 justify-content-end">

                                    <!-- Edit -->
                                    <a href="<?= base_url('subunit/edit/'.$s['id']) ?>"
                                       class="btn btn-sm btn-light border py-0 px-2 text-primary"
                                       data-bs-toggle="tooltip"
                                       title="Edit">

                                        <i class="bi bi-pencil-square"
                                           style="font-size: 0.85rem;"></i>

                                    </a>


                                    <!-- Delete -->
                                    <form action="<?= base_url('subunit/delete/'.$s['id']) ?>"
                                          method="post"
                                          class="d-inline">

                                        <?= csrf_field() ?>

                                        <input type="hidden"
                                               name="subunit_id"
                                               value="<?= esc($s['id']) ?>">

                                        <button type="submit"
                                                onclick="return confirm('Delete this subunit?')"
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