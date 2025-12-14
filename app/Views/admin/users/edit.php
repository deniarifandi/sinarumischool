<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white text-capitalize ps-3 mb-0">
                Edit User
            </h6>
        </div>
    </div>

    <div class="card-body">

        <!-- SUCCESS -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- ERROR -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- VALIDATION ERRORS -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">

                <!-- Username -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        value="<?= old('username', $user['username']) ?>"
                        required
                    >
                </div>

                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?= old('name', $user['name']) ?>"
                        required
                    >
                </div>

                <!-- Role -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-select" required>
                        <?php
                        $roles = ['admin', 'guru', 'siswa', 'operator'];
                        foreach ($roles as $role):
                        ?>
                            <option value="<?= $role ?>"
                                <?= old('role', $user['role']) === $role ? 'selected' : '' ?>>
                                <?= ucfirst($role) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        New Password <span class="text-muted">(optional)</span>
                    </label>

                    <input
                        type="password"
                        name="new_password"
                        class="form-control"
                        autocomplete="new-password"
                        placeholder="Leave blank to keep current password"
                    >

                </div>

                <!-- Divisions -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Divisions</label>
                    <div class="row">
                        <?php foreach ($divisions as $d): ?>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="divisions[]"
                                        value="<?= $d['id'] ?>"
                                        id="div<?= $d['id'] ?>"
                                        <?= in_array($d['id'], old('divisions', $userDivisions)) ? 'checked' : '' ?>
                                    >
                                    <label class="form-check-label" for="div<?= $d['id'] ?>">
                                        <?= esc($d['division_name']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Update User
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>
