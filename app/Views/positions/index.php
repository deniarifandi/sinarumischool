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
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table-solid thead th {
        background-color: #f8f9fa;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 8px 12px;
        border-bottom: 2px solid #dee2e6;
        letter-spacing: 0.5px;
    }

    .table-solid tbody td {
        background-color: #ffffff;
        padding: 6px 12px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        font-size: 13px;
        color: #1a202c;
    }

    .table-solid tbody tr:hover td {
        background-color: #f1f5f9 !important;
    }

    .badge-solid {
        display: inline-block;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 3px;
        background: #edf2f7;
        color: #4a5568;
        border: 1px solid #cbd5e0;
    }

    .btn-flat {
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all 0.1s;
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

    .modal-content.custom-solid-modal {
        border-radius: 4px;
        border: 1px solid #cbd5e0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .modal-header-solid {
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer-solid {
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    .modal-sm { max-width: 380px; }
</style>

<?php if (session('success')): ?>
  <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<?php if (session('error')): ?>
  <div class="alert alert-danger">
      <?php $errors = session('error'); ?>
      <?php if (is_array($errors)): ?>
          <ul class="mb-0">
              <?php foreach ($errors as $e): ?>
                  <li><?= esc($e) ?></li>
              <?php endforeach ?>
          </ul>
      <?php else: ?>
          <?= esc($errors) ?>
      <?php endif; ?>
  </div>
<?php endif; ?>

<div class="main-container shadow-sm">
    <div class="d-flex justify-content-between align-items-center p-2 px-3 border-bottom bg-white">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-briefcase-fill me-2"></i>Position (Jabatan) List</h6>
        <a href="#"
           class="btn btn-primary btn-sm fw-bold"
           data-bs-toggle="modal"
           data-bs-target="#positionFormModal"
           data-mode="create">
            <i class="bi bi-plus-lg"></i> NEW
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-solid" id="positionsTable">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th>Position Name</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($positions as $p): ?>
                <tr>
                    <td><span class="badge-solid"><?= esc($p['jabatan_id']) ?></span></td>
                    <td class="fw-bold"><?= esc($p['jabatan_nama']) ?></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="#"
                               class="btn-flat"
                               title="Edit Position"
                               data-bs-toggle="modal"
                               data-bs-target="#positionFormModal"
                               data-mode="edit"
                               data-id="<?= esc($p['jabatan_id']) ?>"
                               data-nama="<?= esc($p['jabatan_nama']) ?>">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="<?= base_url('positions/delete/' . $p['jabatan_id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" onclick="return confirm('Delete this position?')" class="btn-flat btn-flat-danger">
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
</div>

<div class="modal fade" id="positionFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content custom-solid-modal p-0">

            <div class="modal-header-solid d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 13px;" id="positionModalTitle">
                    <i class="bi bi-briefcase me-2 text-dark"></i>Add Position
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 10px;"></button>
            </div>

            <form method="post" id="positionForm">
                <?= csrf_field() ?>

                <div class="p-3">
                    <div class="mb-2">
                        <label class="form-label-sm mb-1 text-dark">Position Name:</label>
                        <input type="text"
                               name="jabatan_nama"
                               id="positionNamaInput"
                               class="form-control form-control-sm"
                               placeholder="e.g. Wali Kelas"
                               required>
                    </div>
                </div>

                <div class="modal-footer-solid d-flex justify-content-end gap-1">
                    <button type="button" class="btn-flat" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold shadow-sm" style="font-size: 12px;">
                        SAVE
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
const positionFormModal = document.getElementById('positionFormModal');

positionFormModal.addEventListener('show.bs.modal', function (event) {
    const btn  = event.relatedTarget;
    const mode = btn.getAttribute('data-mode');

    const titleEl = document.getElementById('positionModalTitle');
    const inputEl = document.getElementById('positionNamaInput');
    const formEl  = document.getElementById('positionForm');

    if (mode === 'edit') {
        const id   = btn.getAttribute('data-id');
        const nama = btn.getAttribute('data-nama');

        titleEl.innerHTML = '<i class="bi bi-briefcase me-2 text-dark"></i>Edit Position';
        inputEl.value = nama;
        formEl.action = "<?= base_url('positions/save/') ?>" + id;
    } else {
        titleEl.innerHTML = '<i class="bi bi-briefcase me-2 text-dark"></i>Add Position';
        inputEl.value = '';
        formEl.action = "<?= base_url('positions/save') ?>";
    }
});
</script>

<script>
$(function () {
    $('#positionsTable').DataTable({
        pageLength: 10,
        language: {
            paginate: { previous: '‹', next: '›' }
        }
    });
});
</script>
<?= $this->endSection() ?>