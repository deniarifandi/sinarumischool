<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    .form-container-solid {
        max-width: 400px;
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .form-header {
        padding: 12px 16px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .form-body { padding: 20px; }

    /* Compact Inputs */
    .form-label-sm {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }

    .form-select-sm-custom {
        font-size: 13px;
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #cbd5e0;
        color: #1a202c;
        width: 100%;
    }

    .form-select-sm-custom:focus {
        border-color: #3182ce;
        outline: 0;
        box-shadow: 0 0 0 2px rgba(49, 130, 206, 0.1);
    }

    /* Solid Action Buttons */
    .btn-solid-sm {
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 4px;
        transition: all 0.1s;
    }
</style>

<div class="d-flex justify-content-center mt-4">
    <div class="form-container-solid">
        <div class="form-header">
            <h6 class="mb-0 fw-bold">
                <i class="bi bi-shield-lock me-2"></i>Edit User Role
            </h6>
            <small class="text-muted">User: <strong><?= esc($user['name']) ?></strong></small>
        </div>

        <div class="form-body">
            <form method="post" action="<?= base_url('users/role/'.$user['id']) ?>">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="form-label-sm">Select New Role</label>
                    <select name="role" class="form-select-sm-custom" required>
                        <?php 
                            $roles = ['superadmin', 'admin', 'teacher', 'staff'];
                            foreach($roles as $role): 
                        ?>
                            <option value="<?= $role ?>" <?= $user['role'] === $role ? 'selected' : '' ?>>
                                <?= ucfirst($role) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('users') ?>" class="btn btn-light border btn-solid-sm text-dark">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-solid-sm shadow-sm">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>