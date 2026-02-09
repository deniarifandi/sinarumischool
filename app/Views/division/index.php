<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    body { background-color: #f4f7f6; }

    .main-container {
        width: 100%;
        background: #ffffff;
        border: 1px solid #dee2e6;
    }

    .table-solid {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .table-solid thead th {
        background-color: #f8f9fa;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 8px 12px;
        border-bottom: 2px solid #dee2e6;
        letter-spacing: .5px;
    }

    .table-solid tbody td {
        background-color: #ffffff;
        padding: 6px 12px;
        border-bottom: 1px solid #edf2f7;
        font-size: 13px;
        color: #1a202c;
        vertical-align: middle;
    }

    .table-solid tbody tr:hover td {
        background-color: #f1f5f9;
    }

    .badge-solid {
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 3px;
        background: #edf2f7;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-flat {
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: .1s;
    }
    .btn-flat:hover {
        background: #3182ce;
        color: #fff;
        border-color: #2b6cb0;
    }
    .btn-flat-danger:hover {
        background: #e53e3e;
        color: #fff;
        border-color: #c53030;
    }
</style>

<div class="main-container shadow-sm">
    <div class="d-flex justify-content-between align-items-center p-2 px-3 border-bottom bg-white">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-diagram-3-fill me-2"></i>Division Management
        </h6>
        <a href="<?= base_url('division/create') ?>" class="btn btn-primary btn-sm fw-bold">
            <i class="bi bi-plus-lg"></i> NEW
        </a>
    </div>

    <?php if (empty($division)): ?>
        <div class="text-center py-5 text-muted small">
            <i class="bi bi-folder-x fs-3 d-block mb-2"></i>
            No division found
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table-solid">
                <thead>
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Division Name</th>
                        <th class="text-end" style="width:100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($division as $d): ?>
                    <tr>
                        <td>
                            <span class="badge-solid"><?= esc($d['id']) ?></span>
                        </td>
                        <td class="fw-semibold">
                            <?= esc($d['division_name']) ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <a href="<?= base_url('division/edit/'.$d['id']) ?>"
                                   class="btn-flat"
                                   title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form action="<?= base_url('division/delete/'.$d['id']) ?>"
                                      method="post"
                                      class="d-inline">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                            onclick="return confirm('Delete?')"
                                            class="btn-flat btn-flat-danger"
                                            title="Delete">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
