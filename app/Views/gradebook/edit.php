<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<?php
$backUrl = base_url('gradebook') . '?' . http_build_query([
    // 'academic_year' => $academicYearId,
    // 'semester'      => $semesterId,
    'term'          => $termId,
    'class'         => $classId,
    'subject_id'    => $subjectId,
]);
$isLocked = !empty($isLocked);
$kkm = $subject['kkm'] ?? 75; // fallback kalau kolom kkm belum ada di tabel subjects
$oldInput = session()->getFlashdata('old_input');

// Helper: ambil value dari old_input (kalau habis gagal validasi) atau dari DB
function fieldValue($oldInput, $field, $studentId, $dbFallback)
{
    if ($oldInput && isset($oldInput[$field][$studentId])) {
        return $oldInput[$field][$studentId];
    }
    return $dbFallback ?? '';
}
?>

<div class="glass-card p-3">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h6 class="mb-0 fw-bold">Gradebook - <?= esc($subject['subject_name'] ?? '-') ?></h6>
            <small class="text-white-50" style="font-size: 0.75rem;">
                <?= esc($class['class_name'] ?? '-') ?> |
                <?= esc($term['name'] ?? '-') ?> |
                <?= esc($semester['name'] ?? '-') ?> |
                <?= esc($academicYear['name'] ?? '-') ?>
            </small>
        </div>
        <div>
            <a href="<?= esc($backUrl) ?>" id="backBtn" class="btn btn-sm btn-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- VALIDATION ERRORS (reject-all) -->
    <?php if (session()->getFlashdata('validation_errors')): ?>
    <div class="alert alert-danger py-2 mb-2 small">
        <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Nilai tidak valid, tidak ada yang tersimpan:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach (session()->getFlashdata('validation_errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- LOCK STATUS / INSTRUCTIONS -->
    <?php if ($isLocked): ?>
        <div class="alert alert-warning py-1 mb-2 small">
            <i class="bi bi-lock-fill me-1"></i> <strong>Locked</strong> - Scores can no longer be edited.
        </div>
    <?php else: ?>
        <div class="alert alert-info py-1 mb-2 small">
            <i class="bi bi-info-circle me-1"></i> Paste directly from Excel into the first cell using <strong>Ctrl + V</strong>.
        </div>
    <?php endif; ?>

    <!-- FILTER -->
    <div class="row g-2 mb-2 align-items-center">
        <div class="col-auto">
            <label class="form-label mb-0 small text-white-50">Religion:</label>
        </div>
        <div class="col-auto">
            <select id="religionFilter" class="form-select form-select-sm" style="width: 150px;">
                <option value="">All</option>
                <?php
                $religions = [];
                foreach ($students as $student) {
                    if (!empty($student['murid_agama'])) {
                        $religion = trim($student['murid_agama']);
                        if (!in_array($religion, $religions)) $religions[] = $religion;
                    }
                }
                sort($religions);
                foreach ($religions as $religion): ?>
                    <option value="<?= esc(strtolower($religion)) ?>"><?= esc($religion) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col text-end">
            <span class="badge bg-secondary"><span id="studentCount"><?= count($students) ?></span> Students</span>
        </div>
    </div>

    <!-- FORM -->
    <form method="post" action="<?= base_url('gradebook/save') ?>" id="gradebookForm">
        <?= csrf_field() ?>


        <input type="hidden" name="term_id" value="<?= esc($termId) ?>">
        <input type="hidden" name="class_id" value="<?= esc($classId) ?>">
        <input type="hidden" name="subject_id" value="<?= esc($subjectId) ?>">

        <!-- TABLE -->
        <div class="table-responsive" style="border-radius:8px; overflow:auto; max-height:72vh; border: 1px solid rgba(255,255,255,0.1);">
            <table class="table table-sm table-bordered align-middle mb-0" id="gradebookTable" data-kkm="<?= esc($kkm) ?>" style="font-size: 0.85rem;">
                <thead class="table-light" style="position:sticky; top:0; z-index:10;">
                    <tr>
                        <th rowspan="2" class="text-center py-1" style="width:40px; min-width:40px;">No</th>
                        <th rowspan="2" class="py-1" style="min-width:180px; position:sticky; left:0; z-index:11; background:#f8f9fa;">Student</th>
                        <th rowspan="2" class="text-center py-1" style="min-width:90px; position:sticky; left:180px; z-index:11; background:#f8f9fa;">Religion</th>
                        <th colspan="2" class="text-center py-1">Chapter Test 1</th>
                        <th colspan="2" class="text-center py-1">Chapter Test 2</th>
                        <th rowspan="2" class="text-center py-1" style="min-width:90px; line-height:1.1;">Indiv.<br>Project</th>
                        <th rowspan="2" class="text-center py-1" style="min-width:90px; line-height:1.1;">Group<br>Project</th>
                    </tr>
                    <tr>
                        <th class="text-center py-1" style="min-width:70px;">CT1</th>
                        <th class="text-center py-1" style="min-width:80px;">Rem.</th>
                        <th class="text-center py-1" style="min-width:70px;">CT2</th>
                        <th class="text-center py-1" style="min-width:80px;">Rem.</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $index => $student): ?>
                        <?php
                        $studentId = $student['id'];
                        $studentScore = $scores[$studentId] ?? [];
                        $religion = $student['murid_agama'] ?? '';
                        $readOnly = $isLocked ? 'readonly' : '';
                        ?>
                        <tr class="student-row" data-religion="<?= esc(strtolower(trim($religion))) ?>">
                            <td class="text-center text-muted py-1"><?= $index + 1 ?></td>
                            <td class="py-1" style="position:sticky; left:0; z-index:5; background:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:180px;">
                                <input type="hidden" name="student_id[]" value="<?= esc($studentId) ?>">
                                <div class="fw-bold text-truncate" title="<?= esc($student['name']) ?>"><?= esc($student['name']) ?></div>
                            </td>
                            <td class="text-center py-1" style="position:sticky; left:180px; z-index:5; background:#fff;">
                                <?= !empty($religion) ? esc($religion) : '<span class="text-muted">-</span>' ?>
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
                                <input type="text" inputmode="decimal"
                                       name="<?= $field ?>[<?= $studentId ?>]"
                                       value="<?= esc(fieldValue($oldInput, $field, $studentId, $dbValue)) ?>"
                                       class="form-control form-control-sm px-1 text-center grade-cell"
                                       data-col="<?= $colIndex ?>"
                                       autocomplete="off" spellcheck="false" <?= $readOnly ?>>
                            </td>
                            <?php $colIndex++; endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">No students found in this class.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="text-muted small" style="font-size: 0.75rem;">
                <?php if ($isLocked): ?>
                    <i class="bi bi-lock-fill me-1"></i> Read-only mode.
                <?php else: ?>
                    <i class="bi bi-keyboard me-1"></i> Valid scores: 0-100.
                <?php endif; ?>
            </div>
            <div>
                <a href="<?= esc($backUrl) ?>" id="cancelBtn" class="btn btn-sm btn-secondary rounded-pill px-3 me-2">Cancel</a>
                <?php if (!$isLocked): ?>
                    <button type="submit" id="saveBtn" class="btn btn-sm btn-primary rounded-pill px-4"><i class="bi bi-save me-1"></i> Save</button>
                <?php else: ?>
                    <span class="btn btn-sm btn-warning rounded-pill px-4"><i class="bi bi-lock-fill me-1"></i> Locked</span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function () {
    const isLocked = <?= $isLocked ? 'true' : 'false' ?>;
    const kkm = parseFloat($('#gradebookTable').data('kkm')) || 75;

    let formDirty = false;

    // ============================================================
    // Religion Filter
    // ============================================================
    $('#religionFilter').on('change', function () {
        const selectedReligion = $(this).val().toLowerCase();
        let visibleCount = 0;

        $('.student-row').each(function () {
            const rowReligion = ($(this).data('religion') || '').toString().toLowerCase();
            if (selectedReligion === '' || rowReligion === selectedReligion) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });
        $('#studentCount').text(visibleCount);
    });

    // ============================================================
    // Core Validation & Formatting
    // ============================================================
    function validateAndFormatCell($cell) {
        let value = $cell.val().trim();

        if (value === '-' || value === '') {
            $cell.val('').removeClass('is-invalid text-danger fw-bold');
            return true;
        }

        value = value.replace(',', '.');
        $cell.val(value);
        let number = parseFloat(value);

        if (isNaN(number) || number < 0 || number > 100) {
            $cell.addClass('is-invalid').removeClass('text-danger fw-bold');
            return false;
        }

        $cell.removeClass('is-invalid');
        if (number < kkm) {
            $cell.addClass('text-danger fw-bold');
        } else {
            $cell.removeClass('text-danger fw-bold');
        }
        return true;
    }

    $('.grade-cell').each(function () { validateAndFormatCell($(this)); });

    if (!isLocked) {

        // ============================================================
        // Dirty-state tracking (warn before leaving with unsaved changes)
        // ============================================================
        $('.grade-cell').on('input', function () {
            validateAndFormatCell($(this));
            formDirty = true;
        });

        window.addEventListener('beforeunload', function (e) {
            if (formDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Back / Cancel buttons should respect dirty state too (in-app nav)
        $('#backBtn, #cancelBtn').on('click', function (e) {
            if (formDirty) {
                const proceed = confirm('Ada perubahan yang belum disimpan. Yakin ingin keluar?');
                if (!proceed) {
                    e.preventDefault();
                }
            }
        });

        // ============================================================
        // Keyboard navigation (Excel-like)
        // ============================================================
        $('.grade-cell').on('keydown', function (e) {
            const col = parseInt($(this).data('col'));
            const $currentRow = $(this).closest('tr');
            let target = null;

            if (e.key === 'Tab' && !e.shiftKey) {
                e.preventDefault();
                target = $currentRow.find('.grade-cell[data-col="' + (col + 1) + '"]');
                if (!target.length) target = $currentRow.nextAll(':visible').first().find('.grade-cell[data-col="0"]');
            } else if (e.key === 'Tab' && e.shiftKey) {
                e.preventDefault();
                target = $currentRow.find('.grade-cell[data-col="' + (col - 1) + '"]');
                if (!target.length) target = $currentRow.prevAll(':visible').first().find('.grade-cell[data-col="5"]');
            } else if (e.key === 'Enter' || e.key === 'ArrowDown') {
                e.preventDefault();
                target = $currentRow.nextAll(':visible').first().find('.grade-cell[data-col="' + col + '"]');
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                target = $currentRow.prevAll(':visible').first().find('.grade-cell[data-col="' + col + '"]');
            } else if (e.key === 'ArrowRight' && this.selectionStart === this.value.length) {
                target = $currentRow.find('.grade-cell[data-col="' + (col + 1) + '"]');
            } else if (e.key === 'ArrowLeft' && this.selectionStart === 0) {
                target = $currentRow.find('.grade-cell[data-col="' + (col - 1) + '"]');
            }

            if (target && target.length) {
                target.focus();
                target[0].select();
            }
        });

        // ============================================================
        // Paste from Excel
        // ============================================================
        $('.grade-cell').on('paste', function (e) {
            e.preventDefault();
            const text = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
            if (!text) return;

            // Warn if a religion filter is active — pasting would skip hidden rows,
            // silently landing data on the wrong students.
            const activeFilter = $('#religionFilter').val();
            if (activeFilter !== '') {
                const proceed = confirm(
                    'Filter Religion sedang aktif ("' + $('#religionFilter option:selected').text() + '"). ' +
                    'Paste akan melompati baris yang tersembunyi dan bisa salah menempatkan nilai.\n\n' +
                    'Reset filter dan lanjutkan paste?'
                );
                if (!proceed) return;
                $('#religionFilter').val('').trigger('change');
            }

            const rows = text.replace(/\r\n/g, '\n').replace(/\r/g, '\n').split('\n').filter((r, i, arr) => !(r === '' && i === arr.length - 1));
            const startCol = parseInt($(this).data('col'));
            const totalCols = 6; // ct1, ct1_remedial, ct2, ct2_remedial, individual_project, group_project

            // Detect column-count mismatch on the first row and warn once
            const firstRowCols = rows[0] ? rows[0].split('\t').length : 0;
            if (firstRowCols > (totalCols - startCol)) {
                const proceed = confirm(
                    'Data yang di-paste punya ' + firstRowCols + ' kolom, tapi hanya ' + (totalCols - startCol) +
                    ' kolom nilai tersedia mulai dari sel ini.\n\n' +
                    'Kolom berlebih akan diabaikan. Lanjutkan?'
                );
                if (!proceed) return;
            }

            let $currentRow = $(this).closest('tr');

            rows.forEach(function (rowData) {
                if (!$currentRow.length) return;

                const columns = rowData.split('\t');
                columns.forEach(function (value, colIndex) {
                    const target = $currentRow.find('.grade-cell[data-col="' + (startCol + colIndex) + '"]');
                    if (target.length) {
                        target.val(value.trim());
                        validateAndFormatCell(target);
                        formDirty = true;
                    }
                });
                $currentRow = $currentRow.nextAll(':visible').first();
            });

            $(this).focus();
        });

        // ============================================================
        // Submit: validate, then lock the button to prevent double-submit
        // ============================================================
        $('#gradebookForm').on('submit', function (e) {
            let invalid = false;
            $('.grade-cell:visible').each(function () {
                if (!validateAndFormatCell($(this))) invalid = true;
            });

            if (invalid) {
                e.preventDefault();
                alert('Some scores are invalid. Scores must be between 0 and 100.');
                $('.grade-cell.is-invalid:visible').first().focus();
                return false;
            }

            formDirty = false; // allow navigation away after a valid submit
            $('#saveBtn').prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
        });
    }
});
</script>

<?php if (session()->getFlashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success', title: 'Success',
    text: '<?= esc(session()->getFlashdata('success')) ?>',
    timer: 2000, showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error', title: 'Error',
    text: '<?= esc(session()->getFlashdata('error')) ?>'
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>