<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    /* Full Width & Solid Backgrounds */
    body { background-color: #f4f7f6; } /* Standard light grey background */
    
    .main-container {
        width: 100%;
        background: #ffffff;
        border: 1px solid #dee2e6;
    }

    /* Ultra-Compact Table */
    .table-solid {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table-solid thead th {
        background-color: #f8f9fa; /* Solid Light Gray */
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 8px 12px;
        border-bottom: 2px solid #dee2e6;
        letter-spacing: 0.5px;
    }

    .table-solid tbody td {
        background-color: #ffffff; /* Solid White */
        padding: 6px 12px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
        font-size: 13px;
        color: #1a202c;
    }

    .table-solid tbody tr:hover td {
        background-color: #f1f5f9 !important; /* Row Hover Highlight */
    }

    /* Small, Solid UI Elements */
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

    .badge-role {
        background: #ebf8ff;
        color: #2b6cb0;
        border: 1px solid #bee3f8;
    }

    /* Efficient Action Buttons */
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

    .user-info { line-height: 1.2; }
    .user-info small { color: #718096; font-size: 11px; }
</style>

<?php if (session('success')): ?>
  <div class="alert alert-success">
      <?= session('success') ?>
  </div>
<?php endif; ?>


<div class="main-container shadow-sm">
    <div class="d-flex justify-content-between align-items-center p-2 px-3 border-bottom bg-white">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill me-2"></i>User Directory</h6>
        <a href="<?= base_url('users/create') ?>" class="btn btn-primary btn-sm fw-bold">
            <i class="bi bi-plus-lg"></i> NEW
        </a>
    </div>

    <div class="table-responsive">
        <table class="table-solid" id="usersTable">
            <thead>
                <tr>
                    <th style="width: 20%;">Identity</th>
                    <th style="width: 15%;">System Role</th>
                    <th>Divisions</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td>
                        <div class="user-info">
                            <div class="fw-bold"><?= esc($u['name']) ?></div>
                            <small>@<?= esc($u['username']) ?></small>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge-solid badge-role"><?= esc($u['role'] ?? '—') ?></span>
                            <!-- <a href="<?php base_url('users/role/'.$u['id']) ?>" class="btn-flat" title="Edit Role">
                                <i class="bi bi-shield-lock"></i>
                            </a> -->
                                                        <a href="#"
                               class="btn-flat"
                               data-bs-toggle="modal"
                               data-bs-target="#roleModal"
                               data-user-id="<?= $u['id'] ?>"
                               data-user-name="<?= esc($u['name']) ?>"
                               data-user-role="<?= esc($u['role']) ?>">
                                <i class="bi bi-shield-lock"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap align-items-center gap-1">
                            <?php if (!empty($u['divisions'])): ?>
                                <?php foreach ($u['divisions'] as $d): ?>
                                    <span class="badge-solid"><?= esc($d) ?></span>
                                <?php endforeach ?>
                            <?php else: ?>
                                <span class="text-muted small"><em>Unassigned</em></span>
                            <?php endif; ?>
                            <a href="#"
                               class="btn-flat"
                               data-bs-toggle="modal"
                               data-bs-target="#divisionModal"
                               data-user-id="<?= $u['id'] ?>"
                               data-user-name="<?= esc($u['name']) ?>"
                               data-user-divisions='<?= json_encode($u['division_ids']) ?>'>
                                <i class="bi bi-diagram-3"></i>
                            </a>

                        </div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="<?= base_url('users/edit/'.$u['id']) ?>" class="btn-flat" title="Edit User">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="<?= base_url('users/delete/'.$u['id']) ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" onclick="return confirm('Delete?')" class="btn-flat btn-flat-danger">
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


<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<style>
    /* Ensure the modal matches our solid dashboard theme */
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

    /* Target the specific select within the modal for density */
    #roleModal .form-select-sm-custom {
        height: 32px;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .modal-sm {
    max-width: 380px;
    }
    .form-check-input {
        cursor: pointer;
    }

</style>

<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content custom-solid-modal p-0">

            <div class="modal-header-solid d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 13px;">
                    <i class="bi bi-shield-lock me-2 text-dark"></i>Edit Role
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="font-size: 10px;"></button>
            </div>

            <form method="post" id="roleForm">
                <?= csrf_field() ?>

                <div class="p-3">
                    <div class="mb-3 border-start border-primary border-3 ps-2">
                        <small class="text-muted d-block" style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Target User</small>
                        <div class="fw-bold text-dark" id="modalUserName" style="font-size: 14px;"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-sm mb-1 text-dark">Select System Role:</label>
                        <select name="role"
                                id="modalUserRole"
                                class="form-control form-select-sm-custom"
                                required>
                            <?php foreach (['superadmin','admin','teacher','staff'] as $r): ?>
                                <option value="<?= $r ?>"><?= ucfirst($r) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="modal-footer-solid d-flex justify-content-end gap-1">
                    <button type="button"
                            class="btn-flat"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            class="btn btn-primary btn-sm px-3 fw-bold shadow-sm"
                            style="font-size: 12px;">
                        UPDATE
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
    /* Compact Checkbox List */
    .division-item {
        padding: 4px 8px;
        border-radius: 4px;
        transition: background 0.1s;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid transparent;
    }

    .division-item:hover {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
    }

    .division-item input[type="checkbox"] {
        cursor: pointer;
    }

    /* Sub-header for User Identity */
    .modal-user-subtitle {
        background: #f8fafc;
        border-bottom: 1px solid #dee2e6;
        padding: 6px 16px;
    }
</style>

<div class="modal fade" id="divisionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content custom-solid-modal p-0">

            <div class="d-flex justify-content-between align-items-center p-2 px-3 bg-white">
                <h6 class="mb-0 fw-bold" style="font-size: 13px;">
                    <i class="bi bi-diagram-3 me-2 text-primary"></i>Assign Divisions
                </h6>
                <button type="button" class="btn-close" style="font-size: 10px;" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-user-subtitle">
                <small class="text-muted text-uppercase fw-bold" style="font-size: 9px; letter-spacing: 0.5px;">Managing Divisions For:</small>
                <div class="text-dark fw-bold" id="divisionUserName" style="font-size: 14px; line-height: 1.2;"></div>
            </div>

            <form method="post" id="divisionForm">
                <?= csrf_field() ?>

                <div class="p-3" style="max-height: 400px; overflow-y: auto;">
                    <label class="form-label-sm mb-2">Available Divisions</label>
                    <div class="row g-1">
                        <?php foreach ($divisions as $d): ?>
                        <div class="col-6">
                            <label class="division-item">
                                <input type="checkbox" 
                                       class="form-check-input mt-0 division-checkbox" 
                                       name="divisi[]" 
                                       value="<?= $d['id'] ?>">
                                <span class="small text-dark text-truncate"><?= esc($d['division_name']) ?></span>
                            </label>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-1 p-2 px-3 border-top bg-light">
                    <button type="button" class="btn-flat" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm">
                        SAVE CHANGES
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
const roleModal = document.getElementById('roleModal');

roleModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    const id   = btn.getAttribute('data-user-id');
    const name = btn.getAttribute('data-user-name');
    const role = btn.getAttribute('data-user-role');

    document.getElementById('modalUserName').textContent = name;
    document.getElementById('modalUserRole').value = role;

    document.getElementById('roleForm').action =
        "<?= base_url('users/role/') ?>" + id;
});
</script>

<script>
const divisionModal = document.getElementById('divisionModal');

divisionModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    const userId   = btn.getAttribute('data-user-id');
    const userName = btn.getAttribute('data-user-name');
    const userDivs = JSON.parse(btn.getAttribute('data-user-divisions') || '[]');

    document.getElementById('divisionUserName').textContent = userName;
    document.getElementById('divisionForm').action =
        "<?= base_url('users/division/') ?>" + userId;

    document.querySelectorAll('.division-checkbox').forEach(cb => {
        cb.checked = userDivs.includes(parseInt(cb.value));
    });
});
</script>

<script>
$(function () {
    $('#usersTable').DataTable({
        pageLength: 10,
        language: {
            paginate: {
                previous: '‹',
                next: '›'
            }
        }
    });
});
</script>

<?= $this->endSection() ?>