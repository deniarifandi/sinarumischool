<?php
/**
 * Expected vars:
 * $objectives, $students, $objectiveScores, $isLocked,
 * $isReligionSubject, $subjectReligion, $religionMap,
 * $gradebookId, $subjectId, $classId, $termId, $kkm, $religions
 */
?>

<?php
// ---------- Defaults: guard against missing passed data ----------
$objectives         = $objectives         ?? [];
$students           = $students           ?? [];
$objectiveScores    = $objectiveScores    ?? [];
$isLocked           = $isLocked           ?? false;
$isReligionSubject  = $isReligionSubject  ?? false;
$subjectReligion    = $subjectReligion    ?? null;
$religionMap        = $religionMap        ?? [];
$gradebookId        = $gradebookId        ?? null;
$subjectId          = $subjectId          ?? null;
$classId            = $classId            ?? null;
$termId             = $termId             ?? null;
$kkm                = $kkm                ?? 75;
$religions          = $religions          ?? [];
?>

<div class="alert alert-info py-2 mb-3 small">
    <i class="bi bi-info-circle me-1"></i>
    Kolom objektif <strong>otomatis</strong> muncul berdasarkan
    <strong>term</strong> gradebook ini <a href="<?= base_url('outcome?subject_id=' . esc($subjectId)) ?>" class="btn btn-sm btn-primary ms-3 text-nowrap">
            <i class="bi bi-bullseye me-1"></i> Go to Outcome
        </a> untuk mengelola Outcome-Objective. <br>
</div>

<?php if ($isLocked): ?>
    <div class="alert alert-warning py-2 mb-3 small">
        <i class="bi bi-lock-fill me-1"></i>
        This gradebook is locked. Objective scores cannot be edited.
    </div>
<?php endif; ?>

<?php if ($isReligionSubject): ?>
    <div class="alert alert-secondary py-1 mb-2 small">
        <i class="bi bi-info-circle me-1"></i>
        This is a religion subject: <strong><?= esc($subjectReligion ?? 'Unknown') ?></strong>.
        Students from other religions are shown in grey and cannot be edited.
    </div>
<?php endif; ?>

<?php if (!empty($objectives)): ?>

    <!-- ====================================================
         FILTER (OBJECTIVE TAB)
         ==================================================== -->

    <div class="row g-2 mb-2 align-items-center">

        <div class="col-auto">
            <label class="form-label mb-0 small text-white-50">Religion:</label>
        </div>

        <div class="col-auto">
            <select id="objReligionFilter" class="form-select form-select-sm" style="width: 150px;">
                <option value="">All</option>
                <?php foreach ($religions as $religion): ?>
                    <option value="<?= esc(strtolower($religion)) ?>"><?= esc($religion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col text-end">
            <span class="badge bg-secondary">
                <span id="objStudentCount"><?= count($students) ?></span> Students
            </span>
        </div>

    </div>

    <form method="post" action="<?= base_url('gradebook/objective-save') ?>" id="objForm">

        <?= csrf_field() ?>

        <input type="hidden" name="gradebook_id" value="<?= esc($gradebookId) ?>">
        <input type="hidden" name="subject_id" value="<?= esc($subjectId) ?>">
        <input type="hidden" name="class_id" value="<?= esc($classId) ?>">
        <input type="hidden" name="term_id" value="<?= esc($termId) ?>">

        <?php foreach ($objectives as $obj): ?>
            <input type="hidden" name="objective_id[]" value="<?= esc($obj['objective_id']) ?>">
        <?php endforeach; ?>

        <div class="table-responsive" style="border-radius:8px; overflow:auto; max-height:72vh; border:1px solid rgba(255,255,255,0.1);">

            <table class="table table-sm table-bordered align-middle mb-0" id="objTable" data-kkm="<?= esc($kkm) ?>" style="font-size:0.85rem;">

                <thead class="table-light" style="position:sticky; top:0; z-index:10;">
                    <tr>
                        <th class="text-center py-1" style="width:40px; min-width:40px;">No</th>

                        <th class="py-1" style="min-width:180px; position:sticky; left:0; z-index:11; background:#f8f9fa;">
                            Student
                        </th>

                        <th class="text-center py-1" style="min-width:90px; position:sticky; left:180px; z-index:11; background:#f8f9fa;">
                            Religion
                        </th>

                        <?php foreach ($objectives as $obj): ?>
                            <th class="text-center py-1" style="min-width:120px; line-height:1.1;">
                                <div class="small fw-bold"><?= esc($obj['objective_name'] ?? '-') ?></div>
                                <div class="small text-muted" style="font-size:0.7rem;"><?= esc($obj['outcome_name'] ?? '') ?></div>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($students)): ?>

                    <?php foreach ($students as $index => $student): ?>

                        <?php
                        $studentId = $student['id'];
                        $religion  = trim($student['murid_agama'] ?? '');

                        $normalizedStudentReligion = strtolower($religion);
                        $normalizedStudentReligion = $religionMap[$normalizedStudentReligion] ?? null;

                        $religionMismatch = (
                            $isReligionSubject &&
                            $subjectReligion !== null &&
                            $normalizedStudentReligion !== $subjectReligion
                        );

                        $rowClass = $religionMismatch
                            ? 'obj-student-row religion-disabled'
                            : 'obj-student-row';

                        $objReadOnly = ($isLocked || $religionMismatch) ? 'readonly' : '';
                        ?>

                        <tr class="<?= $rowClass ?>" data-religion="<?= esc(strtolower($religion)) ?>">

                            <td class="text-center text-muted py-1"><?= $index + 1 ?></td>

                            <td class="py-1 student-name-cell" style="position:sticky; left:0; z-index:5; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">
                                <input type="hidden" name="student_id[]" value="<?= esc($studentId) ?>">
                                <div class="fw-bold text-truncate" title="<?= esc($student['name']) ?>">
                                    <?= esc($student['name']) ?>
                                </div>
                            </td>

                            <td class="text-center py-1 religion-cell" style="position:sticky; left:180px; z-index:5;">
                                <?php if (!empty($religion)): ?>
                                    <?= esc($religion) ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <?php foreach ($objectives as $colIndex => $obj): ?>
                                <td class="p-1">
                                    <input
                                        type="text"
                                        inputmode="decimal"
                                        name="score[<?= esc($obj['objective_id']) ?>][<?= esc($studentId) ?>]"
                                        value="<?= esc($objectiveScores[$obj['objective_id']][$studentId] ?? '') ?>"
                                        class="form-control form-control-sm px-1 text-center obj-cell"
                                        data-col="<?= $colIndex ?>"
                                        autocomplete="off"
                                        spellcheck="false"
                                        <?= $objReadOnly ?>
                                    >
                                </td>
                            <?php endforeach; ?>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="<?= 3 + count($objectives) ?>" class="text-center py-4">
                            No students found in this class.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">

            <div class="text-muted small" style="font-size:0.75rem;">
                <?php if ($isLocked): ?>
                    <i class="bi bi-lock-fill me-1"></i> Read-only mode.
                <?php else: ?>
                    <i class="bi bi-keyboard me-1"></i> Valid scores: 0-100.
                <?php endif; ?>
            </div>

            <div>
                <?php if (!$isLocked): ?>
                    <button type="submit" id="objSaveBtn" class="btn btn-sm btn-primary rounded-pill px-4">
                        <i class="bi bi-save me-1"></i> Save Objective Scores
                    </button>
                <?php else: ?>
                    <span class="btn btn-sm btn-warning rounded-pill px-4">
                        <i class="bi bi-lock-fill me-1"></i> Locked
                    </span>
                <?php endif; ?>
            </div>

        </div>

    </form>

<?php else: ?>

    <div class="alert alert-secondary py-2 mb-2 small d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-info-circle me-1"></i>
            Belum ada objektif untuk term ini. Tambahkan objektif
            (dengan term) di menu Outcome-Objective.
        </div>

        
    </div>

<?php endif; ?>
