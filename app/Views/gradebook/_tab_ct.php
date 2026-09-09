<?php
/**
 * Expected vars:
 * $termId, $classId, $subjectId, $students, $scores, $oldInput,
 * $isLocked, $isReligionSubject, $subjectReligion, $religionMap,
 * $kkm, $religions
 *
 * Uses global helper: fieldValue($oldInput, $field, $studentId, $dbFallback)
 */

// ---------- Defaults: guard against missing passed data ----------
$termId            = $termId            ?? null;
$classId           = $classId           ?? null;
$subjectId         = $subjectId         ?? null;
$students          = $students          ?? [];
$scores            = $scores            ?? [];
$oldInput          = $oldInput          ?? null;
$isLocked          = $isLocked          ?? false;
$isReligionSubject = $isReligionSubject ?? false;
$subjectReligion   = $subjectReligion   ?? null;
$religionMap       = $religionMap       ?? [];
$kkm               = $kkm               ?? 75;
$religions         = $religions         ?? [];
?>

<!-- ============================================================
     FILTER
     ============================================================ -->
<div class="row g-2 mb-3 align-items-center">
    <div class="col-auto">
        <label class="form-label mb-0 small text-secondary fw-semibold">Religion:</label>
    </div>
    <div class="col-auto">
        <select id="religionFilter" class="form-select form-select-sm shadow-none" style="width: 150px;">
            <option value="">All</option>
            <?php foreach (($religions ?? []) as $religion): ?>
                <option value="<?= esc(strtolower($religion)) ?>"><?= esc($religion) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col text-end">
        <span class="badge bg-secondary px-3 py-2 rounded-pill">
            <span id="studentCount"><?= count($students) ?></span> Students
        </span>
    </div>
</div>

<!-- ============================================================
     FORM
     ============================================================ -->
<form method="post" action="<?= base_url('gradebook/save') ?>" id="gradebookForm">
    <?= csrf_field() ?>
    <input type="hidden" name="term_id" value="<?= esc($termId) ?>">
    <input type="hidden" name="class_id" value="<?= esc($classId) ?>">
    <input type="hidden" name="subject_id" value="<?= esc($subjectId) ?>">

    <!-- ========================================================
         TABLE
         ======================================================== -->
    <div class="table-responsive border rounded" style="overflow:auto; max-height:72vh;">
        <table 
            class="table table-sm table-bordered align-middle mb-0" 
            id="gradebookTable" 
            data-kkm="<?= esc($kkm ?? 75) ?>" 
            style="font-size:0.85rem;"
        >
            <thead class="table-light align-middle" style="position:sticky; top:0; z-index:10;">
                <tr>
                    <th rowspan="2" class="text-center py-2" style="width:40px; min-width:40px;">No</th>
                    
                    <th rowspan="2" class="py-2 bg-light" style="min-width:180px; position:sticky; left:0; z-index:11;">
                        Student
                    </th>
                    
                    <th rowspan="2" class="text-center py-2 bg-light" style="min-width:90px; position:sticky; left:180px; z-index:11;">
                        Religion
                    </th>
                    
                    <th colspan="2" class="text-center py-2">Chapter Test 1</th>
                    <th colspan="2" class="text-center py-2">Chapter Test 2</th>
                    
                    <th rowspan="2" class="text-center py-2" style="min-width:90px; line-height:1.2;">
                        Indiv.<br>Project
                    </th>
                    
                    <th rowspan="2" class="text-center py-2" style="min-width:90px; line-height:1.2;">
                        Group<br>Project
                    </th>
                </tr>
                <tr>
                    <th class="text-center py-1 text-muted" style="min-width:70px;">CT1</th>
                    <th class="text-center py-1 text-muted" style="min-width:80px;">Rem.</th>
                    <th class="text-center py-1 text-muted" style="min-width:70px;">CT2</th>
                    <th class="text-center py-1 text-muted" style="min-width:80px;">Rem.</th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $index => $student): ?>
                    <?php
                    $studentId    = $student['id'];
                    $studentScore = $scores[$studentId] ?? [];
                    $religion     = trim($student['murid_agama'] ?? '');

                    $normalizedStudentReligion = strtolower($religion);
                    $normalizedStudentReligion = $religionMap[$normalizedStudentReligion] ?? null;

                    $religionMismatch = (
                        $isReligionSubject &&
                        $subjectReligion !== null &&
                        $normalizedStudentReligion !== $subjectReligion
                    );

                    $rowClass = $religionMismatch ? 'student-row religion-disabled bg-light' : 'student-row';
                    $readOnly = ($isLocked || $religionMismatch) ? 'readonly' : '';
                    ?>

                    <tr class="<?= $rowClass ?>" data-religion="<?= esc(strtolower($religion)) ?>">
                        <td class="text-center text-muted py-1"><?= $index + 1 ?></td>
                        
                        <!-- Perhatikan penambahan bg-white disini agar teks yang scroll tidak tembus -->
                        <td 
                            class="py-1 student-name-cell bg-white" 
                            style="position:sticky; left:0; z-index:5; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;"
                        >
                            <input type="hidden" name="student_id[]" value="<?= esc($studentId) ?>">
                            <div class="fw-semibold text-truncate" title="<?= esc($student['name']) ?>">
                                <?= esc($student['name']) ?>
                            </div>
                        </td>

                        <!-- Perhatikan penambahan bg-white disini -->
                        <td class="text-center py-1 religion-cell bg-white" style="position:sticky; left:180px; z-index:5;">
                            <?php if (!empty($religion)): ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border"><?= esc($religion) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>

                        <?php
                        $cols = [
                            'ct1'                => $studentScore['ct1'] ?? null,
                            'ct1_remedial'       => $studentScore['ct1_remedial'] ?? null,
                            'ct2'                => $studentScore['ct2'] ?? null,
                            'ct2_remedial'       => $studentScore['ct2_remedial'] ?? null,
                            'individual_project' => $studentScore['individual_project'] ?? null,
                            'group_project'      => $studentScore['group_project'] ?? null,
                        ];
                        $colIndex = 0;
                        ?>

                        <?php foreach ($cols as $field => $dbValue): ?>
                            <td class="p-1">
                                <input 
                                    type="text" 
                                    inputmode="decimal" 
                                    name="<?= $field ?>[<?= $studentId ?>]" 
                                    value="<?= esc(fieldValue($oldInput, $field, $studentId, $dbValue)) ?>" 
                                    class="form-control form-control-sm px-1 text-center grade-cell shadow-none <?= $readOnly ? 'bg-light text-muted' : '' ?>" 
                                    data-col="<?= $colIndex ?>" 
                                    autocomplete="off" 
                                    spellcheck="false" 
                                    <?= $readOnly ?>
                                >
                            </td>
                            <?php $colIndex++; ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        No students found in this class.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ========================================================
         FOOTER
         ======================================================== -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-secondary small fw-medium">
            <?php if ($isLocked): ?>
                <i class="bi bi-lock-fill me-1 text-warning"></i> Read-only mode.
            <?php else: ?>
                <i class="bi bi-info-circle me-1 text-primary"></i> Valid scores: 0-100.
            <?php endif; ?>
        </div>

        <div>
            <?php if (!$isLocked): ?>
                <button type="submit" id="saveBtn" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="bi bi-save me-1"></i> Save Changes
                </button>
            <?php else: ?>
                <span class="btn btn-warning rounded-pill px-4" style="cursor: not-allowed;">
                    <i class="bi bi-lock-fill me-1"></i> Locked
                </span>
            <?php endif; ?>
        </div>
    </div>
</form>