<?php
if (!function_exists('safe_url')) {
    function safe_url($path, $fallback = 'avatar/default.png') {
        return $path ? base_url($path) : base_url($fallback);
    }
}
?>

<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        --card-border-radius: 12px;
    }
    
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .profile-avatar-wrap {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: var(--card-border-radius);
        padding: 1.25rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .dashboard-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: var(--card-border-radius);
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .dashboard-card-header {
        background: #f9fafb;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        font-weight: 600;
        color: #1f2937;
    }

    .dashboard-card-body {
        padding: 1.25rem;
    }

    .nav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }

    .nav-item-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #334155;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .nav-item-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateX(2px);
    }

    .nav-item-btn i {
        font-size: 1.25rem;
        color: #64748b;
    }

    .nav-item-btn:hover i {
        color: #3b82f6;
    }
</style>

<div class="dashboard-container py-4">

    <!-- Alert Block -->
    <?php if (empty($checkedToday)): ?>
        <div class="alert alert-danger d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" role="alert" style="border-radius: var(--card-border-radius);">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>Attendance has not been submitted today.</div>
            </div>
            <a href="<?= base_url('presence') ?>" class="btn btn-sm btn-light text-danger fw-bold px-3">Submit Now</a>
        </div>
    <?php endif; ?>

    <?php if ($attendanceMissing && $mainClass['id']): ?>
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
                <strong>Attendance Reminder</strong><br>
                Student Attendance for today has not been recorded.
            </div>

            <a href="<?= base_url('student/attendance/create/'.$mainClass['id']) ?>"
               class="btn btn-warning btn-sm">
                Take Attendance
            </a>
        </div>
        <?php endif; ?>

    <!-- Header & Profile Card -->
    <div class="card border-0 shadow-sm mb-4 bg-light" style="border-radius: var(--card-border-radius);">
        <div class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="profile-avatar-wrap">
                        <img src="<?= safe_url($user['pasfoto']) ?>" class="profile-avatar" alt="Avatar">
                    </div>
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle" title="Online"></span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="mb-0 fw-bold text-dark"><?= esc($user['name'] ?? 'Personnel') ?></h4>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.75rem;">
                            <?= esc($user['role'] ?? 'Unpositioned') ?>
                        </span>
                    </div>
               <p class="text-muted small mb-0">
                <?php if (!empty($mainClass['class_name'])): ?>
                    <i class="bi bi-door-open me-1"></i>

                    <?= $mainClass['teacher_role'] === 'assistant'
                        ? 'Assistant Class Teacher of'
                        : 'Class Teacher of' ?>:

                    <strong><?= esc($mainClass['class_name']) ?></strong>
                <?php else: ?>
                    <i class="bi bi-person-badge me-1"></i> Operations Console
                <?php endif ?>
            </p>
                </div>
            </div>

            <!-- Division Switcher Global Control -->
            <div style="min-width: 260px;">
                <label class="form-label text-muted small fw-bold mb-1"><i class="bi bi-sliders me-1"></i> Active Division Workspace</label>
                <select class="form-select form-select-md shadow-sm border-secondary-subtle" id="divisionSwitcher">
                    <?php foreach ($divisions as $d): ?>
                        <option value="<?= esc($d['id']) ?>">
                            <?= esc($d['division_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Metrics / Card Numbers Row -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium d-block mb-1">Assigned Divisions</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= count($divisions) ?></h3>
                </div>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-building"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium d-block mb-1">Active Subjects</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= is_array($userSubjects) ? count($userSubjects) : 0 ?></h3>
                </div>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium d-block mb-1">Class Responsibility</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= ($mainClass['class_name'] ?? false) ? '1' : '0' ?></h3>
                </div>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-door-open"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-medium d-block mb-1">Shift Verification</span>
                    <h3 class="fw-bold mb-0 <?= empty($checkedToday) ? 'text-danger' : 'text-success' ?>">
                        <?= empty($checkedToday) ? 'Pending' : 'Verified' ?>
                    </h3>
                </div>
                <div class="stat-icon <?= empty($checkedToday) ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' ?>">
                    <i class="bi bi-patch-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Personal Management Dashboard Block -->
    <div class="dashboard-card">
        <div class="dashboard-card-header"><i class="bi bi-person-workspace me-2 text-secondary"></i>Personal Management</div>
        <div class="dashboard-card-body">
            <div class="nav-grid">
                <a href="<?= base_url('profile') ?>" class="nav-item-btn">
                    <i class="bi bi-person"></i>
                    <span>My Profile</span>
                </a>
                <a href="<?= base_url('presence') ?>" class="nav-item-btn">
                    <i class="bi bi-calendar-check"></i>
                    <span>Log Attendance</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Special Context Content: PPBP Menu Component -->
    <?php 
      $names = array_column($divisions, 'division_name');
      if (in_array('pos paud bunga pelangi', array_map('strtolower', $names))): 
    ?>
    <div class="dashboard-card">
        <div class="dashboard-card-header"><i class="bi bi-stars me-2 text-warning"></i>PPBP Program Engine</div>
        <div class="dashboard-card-body">
            <div class="nav-grid">
                <?php foreach ($userSubjects as $d): ?>
                    <a href="<?= base_url('lessonplan?subject_id=' . $d['subject_id']) ?>" class="nav-item-btn">
                        <i class="bi bi-journal-code"></i>
                        <span>Modul Ajar <?= esc($d['subject_name']) ?></span>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php endif ?>

    <!-- Division Scope Controlled Block (Dynamic filtering shows context matches) -->
    <?php if (in_array($user['role'], ['superadmin', 'teacher', 'admin', 'teacher_admin'])): ?>
    <div class="dashboard-card">
        <div class="dashboard-card-header d-flex align-items-center justify-content-between">
            <div><i class="bi bi-building-gear me-2 text-primary"></i>Division Operational Control</div>
            <span class="badge bg-secondary text-white small px-2 py-1 division-badge-label">Loading Workspace...</span>
        </div>
        <div class="dashboard-card-body">
            
            <!-- Contextual items tied to current selected division -->
            <?php foreach ($divisions as $d): ?>
                <div class="division-pane" data-division-id="<?= esc($d['id']) ?>" style="display:none;">
                    <div class="nav-grid">
                        
                        <?php if (in_array($user['role'], ['superadmin', 'teacher', 'teacher_admin'])): ?>
                            <a href="<?= base_url('socioreport?divisi='.$d['id']) ?>" class="nav-item-btn">
                                <i class="bi bi-heart-pulse"></i>
                                <span>Socio-Emotional Report</span>
                            </a>
                        <?php endif; ?>

                        <?php if ($user['role'] == "superadmin" || $user['role'] == "admin"): ?>
                        <a href="<?= base_url('grade?divisi='.$d['id']) ?>" class="nav-item-btn">
                            <i class="bi bi-award"></i>
                            <span>Grades Management</span>
                        </a>

                        <a href="<?= base_url('class?divisi='.$d['id']) ?>" class="nav-item-btn">
                            <i class="bi bi-diagram-3"></i>
                            <span>Classes Structure</span>
                        </a>
                        <?php endif ?>

                        <a href="<?= base_url('student/dashboard?division='.$d['id']) ?>" class="nav-item-btn">
                            <i class="bi bi-people"></i>
                            <span>Student Registry</span>
                        </a>
                         <?php if ($user['role'] == "superadmin" || $user['role'] == "admin"): ?>
                        <a href="<?= base_url('subject?division='.$d['id']) ?>" class="nav-item-btn">
                            <i class="bi bi-book-half"></i>
                            <span>Subject Management</span>
                        </a>
                    <?php endif ?>
                        <a href="<?= base_url('user-subject?division='.$d['id']) ?>" class="nav-item-btn">
                            <i class="bi bi-person-lines-fill"></i>
                            <span>User Subject Assignments</span>
                        </a>

                    </div>
                </div>
            <?php endforeach; ?>
            
        </div>
    </div>
    <?php endif ?>

    <!-- Class Context Action Modules -->
    <?php if (in_array($user['role'], ['superadmin', 'teacher', 'teacher_admin']) && isset($mainClass['id'])): ?>
    <div class="dashboard-card">
        <div class="dashboard-card-header"><i class="bi bi-door-closed me-2 text-success"></i>Class Room Management</div>
        <div class="dashboard-card-body">
            <div class="nav-grid">
                <a href="<?= base_url('student/attendance/class/'.$mainClass["id"]) ?>" class="nav-item-btn">
                    <i class="bi bi-check2-square"></i>
                    <span>Take Student Presence</span>
                </a>
              <!--   <a href="<?= base_url('student/attendance/list/class/'.$mainClass["id"]) ?>" class="nav-item-btn">
                    <i class="bi bi-list-check"></i>
                    <span>Attendance Overview</span>
                </a> -->
            </div>
        </div>
    </div>
    <?php endif ?>

    <!-- Subject Strategy Elements filtered by Active Selected Division Name matching -->
    <?php if (in_array($user['role'], ['superadmin', 'teacher', 'teacher_admin']) && !empty($userSubjects)): ?>
    <div class="dashboard-card">
        <div class="dashboard-card-header"><i class="bi bi-journal-bookmark me-2 text-danger"></i>Curriculum Subjects Matrix</div>
        <div class="dashboard-card-body">
            
            <?php foreach ($divisions as $di): ?>  
                <div class="division-subject-pane" data-division-name="<?= esc(strtolower($di['division_name'])) ?>" style="display:none;">
                    <div class="row g-3">
                        <?php foreach ($userSubjects as $sub): ?>
                            <?php if (strtolower($di['division_name']) === strtolower($sub['division_name'])): ?>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 border rounded-3 bg-light-subtle">
                                        <div class="fw-bold text-dark mb-2"><i class="bi bi-bookmark-fill text-danger me-2"></i><?= esc($sub['subject_name']) ?></div>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('unit?subject_id='.$sub['subject_id']) ?>" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                <i class="bi bi-collection me-1"></i> Units & Sub-Units
                                            </a>
                                            <a href="<?= base_url('outcome?subject_id='.$sub['subject_id']) ?>" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                                <i class="bi bi-bullseye me-1"></i> Outcom. & Objectives
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
    <?php endif ?>

    <!-- Superadmin Global Context Actions -->
    <?php if ($user['role'] == "superadmin" || $user['role'] == "admin"): ?>
        <div class="dashboard-card">
            <div class="dashboard-card-header text-danger-emphasis"><i class="bi bi-shield-lock-fill me-2"></i>Global Systems Administration</div>
            <div class="dashboard-card-body">
                <div class="nav-grid">
                    <?php if ($user['role'] == "superadmin" || $user['role'] == "admin"): ?>
                        <a href="<?= base_url('division') ?>" class="nav-item-btn">
                            <i class="bi bi-diagram-2"></i>
                            <span>Manage Divisions</span>
                        </a>
                        <a href="<?= base_url('users/dashboard') ?>" class="nav-item-btn">
                            <i class="bi bi-people-fill"></i>
                            <span>System User Control</span>
                        </a>
                        <a href="<?= base_url('roles') ?>" class="nav-item-btn">
                            <i class="bi bi-shield-check"></i>
                            <span>Permissions & Roles</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($user['role'] == "superadmin"): ?>
                        <a href="<?= base_url('attendance') ?>" class="nav-item-btn">
                            <i class="bi bi-clock-history"></i>
                            <span>Staff Master Attendance</span>
                        </a>
                        <a href="<?= base_url('rekap') ?>" class="nav-item-btn">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                            <span>Attendance Reports Recap</span>
                        </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

<!-- Context View Filtering Logic Controller -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const switcher = document.getElementById('divisionSwitcher');
    const STORAGE_KEY = 'selectedDivision';

    function updateWorkspaceView() {
        const selectedId = switcher.value;
        const selectedText = switcher.options[switcher.selectedIndex].text;

        // Save selected division
        localStorage.setItem(STORAGE_KEY, selectedId);

        // Update badge
        document.querySelectorAll('.division-badge-label').forEach(badge => {
            badge.textContent = selectedText;
        });

        // Division menu
        document.querySelectorAll('.division-pane').forEach(pane => {
            pane.style.display =
                pane.dataset.divisionId === selectedId ? 'block' : 'none';
        });

        // Subject section
        document.querySelectorAll('.division-subject-pane').forEach(pane => {
            pane.style.display =
                pane.dataset.divisionName === selectedText.toLowerCase().trim()
                    ? 'block'
                    : 'none';
        });
    }

    if (!switcher) return;

    // Restore previous selection
    const savedDivision = localStorage.getItem(STORAGE_KEY);
    if (savedDivision) {
        const option = switcher.querySelector(`option[value="${savedDivision}"]`);
        if (option) {
            switcher.value = savedDivision;
        }
    }

    switcher.addEventListener('change', updateWorkspaceView);

    // Initial render
    updateWorkspaceView();
});
</script>

<?= $this->endSection() ?>