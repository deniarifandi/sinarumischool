<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<style>
    .subject-card {
        background: rgba(255,255,255,0.035);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .subject-header {
        padding: 16px 20px;
        background: linear-gradient(
            135deg,
            rgba(59,130,246,0.16),
            rgba(255,255,255,0.025)
        );
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .subject-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #f8fafc;
    }

    .subject-code {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.45);
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .grade-table {
        font-size: 0.82rem;
    }

    .grade-table th {
        background: #f8f9fa;
        color: #212529;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    .grade-table td {
        vertical-align: middle;
    }

    .student-name {
        font-weight: 700;
        white-space: nowrap;
    }

    .score {
        text-align: center;
        font-weight: 600;
        min-width: 65px;
    }

    .score.empty {
        color: #adb5bd;
        font-weight: 400;
    }

    .score.low {
        color: #dc3545;
        font-weight: 800;
    }

    .score.good {
        color: #198754;
    }

    .subject-empty {
        padding: 35px;
        text-align: center;
        color: rgba(255,255,255,0.45);
    }

    .summary-badge {
        font-size: .72rem;
    }
</style>

<div class="container-fluid px-0 py-3">

    <!-- HEADER -->
    <div class="glass-card p-4 mb-4">

        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">

            <div>
                <h4 class="fw-bold text-light mb-2">
                    <i class="bi bi-journal-check text-primary me-2"></i>
                    Curriculum Gradebook
                </h4>

                <div class="d-flex flex-wrap gap-2">

                    <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary">
                        <i class="bi bi-people-fill me-1"></i>
                        <?= esc($class['class_name']) ?>
                    </span>

                    <span class="badge rounded-pill bg-success bg-opacity-25 text-success">
                        <i class="bi bi-flag-fill me-1"></i>
                        <?= esc($term['name']) ?>
                    </span>

                    <span class="badge rounded-pill bg-warning bg-opacity-25 text-warning">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?= esc($academicYear['name'] ?? '-') ?>
                    </span>

                    <span class="badge rounded-pill bg-secondary">
                        <?= count($students) ?> Students
                    </span>

                </div>
            </div>

            <div>
                <a
                    type="button"
                    class="btn btn-outline-light rounded-pill px-4">
                    <i class="bi bi-x-lg me-1"></i>
                    Back
                </a>
            </div>

        </div>

    </div>


    <!-- SUBJECTS -->

    <?php if (empty($subjects)): ?>

        <div class="glass-card p-5 text-center text-white-50">
            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
            No subjects found for this division.
        </div>

    <?php else: ?>

        <?php foreach ($subjects as $subjectData): ?>

            <?php
            $subject   = $subjectData['subject'];
            $gradebook = $subjectData['gradebook'];
            $scores    = $subjectData['scores'];

            $kkm = $subject['kkm'] ?? 75;
            ?>

            <div class="subject-card">

                <!-- SUBJECT HEADER -->
                <div class="subject-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="subject-code">
                                <?= esc($subject['subject_code'] ?? '') ?>
                            </div>

                            <div class="subject-title">
                                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>
                                <?= esc($subject['subject_name']) ?>
                            </div>
                        </div>

                        <div>

                            <?php if ($gradebook): ?>

                                <?php if (!empty($gradebook['is_locked'])): ?>

                                    <span class="badge bg-warning text-dark summary-badge">
                                        <i class="bi bi-lock-fill me-1"></i>
                                        Locked
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-success summary-badge">
                                        <i class="bi bi-pencil-fill me-1"></i>
                                        Editable
                                    </span>

                                <?php endif; ?>

                            <?php else: ?>

                                <span class="badge bg-secondary summary-badge">
                                    No Gradebook
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


                <!-- TABLE -->

                <?php if (empty($gradebook)): ?>

                    <div class="subject-empty">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        No gradebook has been created for this subject yet.
                    </div>

                <?php else: ?>

                    <div class="table-responsive">

                        <table class="table table-sm table-bordered grade-table mb-0">

                            <thead>

                                <tr>

                                    <th rowspan="2" style="width:50px;">
                                        No
                                    </th>

                                    <th rowspan="2"
                                        style="min-width:180px;">
                                        Student
                                    </th>

                                    <th colspan="2">
                                        Chapter Test 1
                                    </th>

                                    <th colspan="2">
                                        Chapter Test 2
                                    </th>

                                    <th rowspan="2">
                                        Individual<br>Project
                                    </th>

                                    <th rowspan="2">
                                        Group<br>Project
                                    </th>

                                </tr>

                                <tr>

                                    <th>CT1</th>
                                    <th>Rem.</th>

                                    <th>CT2</th>
                                    <th>Rem.</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($students as $index => $student): ?>

                                    <?php
                                    $studentId = $student['id'];

                                    $studentScore =
                                        $scores[$studentId] ?? [];

                                    $fields = [
                                        'ct1',
                                        'ct1_remedial',
                                        'ct2',
                                        'ct2_remedial',
                                        'individual_project',
                                        'group_project'
                                    ];
                                    ?>

                                    <tr>

                                        <td class="text-center text-muted">
                                            <?= $index + 1 ?>
                                        </td>

                                        <td>
                                            <span class="student-name">
                                                <?= esc($student['name']) ?>
                                            </span>
                                        </td>


                                        <?php foreach ($fields as $field): ?>

                                            <?php
                                            $value = $studentScore[$field] ?? null;

                                            $class = 'score';

                                            if ($value === null || $value === '') {
                                                $class .= ' empty';
                                                $displayValue = '-';
                                            } else {
                                                $displayValue = $value;

                                                if ((float)$value < (float)$kkm) {
                                                    $class .= ' low';
                                                } else {
                                                    $class .= ' good';
                                                }
                                            }
                                            ?>

                                            <td class="<?= $class ?>">
                                                <?= esc($displayValue) ?>
                                            </td>

                                        <?php endforeach; ?>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>