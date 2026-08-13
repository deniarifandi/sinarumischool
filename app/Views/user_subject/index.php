<?= $this->extend('main') ?>

<?= $this->section('content') ?>


<div class="glass-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Subject Users</h5>
            <small class="text-muted">
                Division: <?= esc($division['name'] ?? $division['division_name'] ?? $division['id']) ?>
            </small>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= esc(session()->getFlashdata('error')) ?>
</div>
<?php endif; ?>

<!-- Table Section -->
<div class="table-responsive" style="border-radius:12px; overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
    <table class="table glass-table align-middle mb-0">
        <thead>
            <tr>
                <th style="width:60px;" class="ps-3">#</th>
                <th style="width:180px;">Subject Code</th>
                <th>Subject</th>
                <th>Users</th>
                <?php if (in_array($userDetail['role'], ['superadmin', 'teacher_admin'])): ?>
                    <th style="width:150px;">Action</th>
                <?php endif ?>
            </tr>
        </thead>
        
        <tbody>
            <?php if (empty($subjects)): ?>
                <tr>
                    <td colspan="5" class="text-center py-4">
                        No subjects found for this division.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($subjects as $index => $subject): ?>
                    <tr>
                        <td class="ps-3"><?= $index + 1 ?></td>
                        
                        <td>
                            <span class="badge bg-secondary">
                                <?= esc($subject['subject_code'] ?? '-') ?>
                            </span>
                        </td>
                        
                        <td>
                            <div class="fw-bold">
                                <?= esc($subject['subject_name']) ?>
                            </div>
                            <?php if (!empty($subject['description'])): ?>
                                <small class="text-muted">
                                    <?= esc($subject['description']) ?>
                                </small>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <?php if (empty($subject['users'])): ?>
                                <span class="text-muted">No users assigned</span>
                            <?php else: ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($subject['users'] as $user): ?>
                                        <span class="badge bg-primary">
                                            <?= esc($user['name'] ?? $user['username'] ?? $user['id']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        
                        
                        <?php if (in_array($userDetail['role'], ['superadmin', 'teacher_admin'])): ?>
                           <td>
                            <a href="<?= base_url('user-subject/edit/' . $subject['id']) ?>" class="btn btn-sm btn-primary">
                                Assignment
                            </a>
                        </td>
                    <?php endif ?>
                    
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</div>

</div>

<?= $this->endSection() ?>