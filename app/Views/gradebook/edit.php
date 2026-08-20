<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<?php
$backUrl = base_url('gradebook') . '?' . http_build_query([
    'term'       => $termId,
    'class'      => $classId,
    'subject_id' => $subjectId,
]);

$isLocked = !empty($isLocked);

$kkm = $subject['kkm'] ?? 75;

$oldInput = session()->getFlashdata('old_input');


// ============================================================
// RELIGION NORMALIZATION
// ============================================================

$religionMap = [
    'islam'     => 'islam',

    'christian' => 'christian',
    'kristen'   => 'christian',

    'catholic'  => 'catholic',
    'katolik'   => 'catholic',

    'buddhist'  => 'buddhist',
    'buddha'    => 'buddhist',
    'budha'     => 'buddhist',

    'hindu'     => 'hindu',
];


// ============================================================
// DETECT RELIGION SUBJECT
// Example: Religion : Islam
// ============================================================

$subjectName = trim($subject['subject_name'] ?? '');

$isReligionSubject = false;
$subjectReligion   = null;

if (preg_match('/^Religion\s*:\s*(.+)$/i', $subjectName, $matches)) {

    $isReligionSubject = true;

    $rawReligion = strtolower(trim($matches[1]));

    $subjectReligion = $religionMap[$rawReligion] ?? null;
}


// ============================================================
// HELPER
// ============================================================

function fieldValue($oldInput, $field, $studentId, $dbFallback)
{
    if ($oldInput && isset($oldInput[$field][$studentId])) {
        return $oldInput[$field][$studentId];
    }

    return $dbFallback ?? '';
}
?>

<style>

    /* ============================================================
       NEON HEADER
       ============================================================ */

    .neon-title {
        background: linear-gradient(90deg, #ffff00 0%, #ffcc00 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow:
            0 0 12px rgba(255, 255, 0, 0.7),
            0 0 25px rgba(255, 255, 0, 0.4);
        font-size: 1.4rem;
        letter-spacing: 0.5px;
    }

    .neon-accent {
        border-left: 5px solid #ffff00;
        padding-left: 15px;
        box-shadow: -5px 0 12px -2px rgba(255, 255, 0, 0.7);
        border-radius: 3px;
    }

    .neon-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        margin-right: 0.4rem;
        margin-top: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fffde7;
        background: rgba(255, 255, 0, 0.12);
        border: 1px solid rgba(255, 255, 0, 0.6);
        border-radius: 50px;
        box-shadow:
            0 0 10px rgba(255, 255, 0, 0.4),
            inset 0 0 5px rgba(255, 255, 0, 0.2);
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }


    /* ============================================================
       RELIGION MISMATCH
       ============================================================ */

    .religion-disabled td {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }

    .religion-disabled .student-name-cell,
    .religion-disabled .religion-cell {
        background-color: #e9ecef !important;
        color: #6c757d !important;
    }

    .religion-disabled .grade-cell {
        background-color: #dfe2e5 !important;
        color: #6c757d !important;
        cursor: not-allowed;
    }

    .religion-disabled .grade-cell:focus {
        box-shadow: none;
    }


    /* ============================================================
       NORMAL GRADE CELL
       ============================================================ */

    .grade-cell {
        min-width: 65px;
    }


    /* ============================================================
       LOCKED
       ============================================================ */

    .grade-cell[readonly] {
        cursor: not-allowed;
    }

</style>


<div class="glass-card p-3">

    <!-- ============================================================
         HEADER
         ============================================================ -->

    <div
        class="d-flex justify-content-between align-items-center mb-3 pb-3"
        style="border-bottom: 1px solid rgba(255,255,255,0.08);"
    >

        <div class="neon-accent">

            <h5
                class="m-2 mt-3 mx-2 fw-bold neon-title"
                style="margin-right: 10px;"
            >
                <i class="bi bi-journal-check me-2"></i>

                Gradebook -
                <?= esc($subject['subject_name'] ?? '-') ?>
            </h5>


            <div
                class="d-flex flex-wrap mt-1 mb-3"
                style="margin-right: 10px"
            >

                <span class="neon-badge">
                    <i class="bi bi-people-fill me-1"></i>
                    <?= esc($class['class_name'] ?? '-') ?>
                </span>

                <span class="neon-badge">
                    <i class="bi bi-bookmark-fill me-1"></i>
                    <?= esc($term['name'] ?? '-') ?>
                </span>

                <span class="neon-badge">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= esc($semester['name'] ?? '-') ?>
                </span>

                <span class="neon-badge">
                    <i class="bi bi-clock-history me-1"></i>
                    <?= esc($academicYear['name'] ?? '-') ?>
                </span>

            </div>

        </div>


        <div>

            <button
                type="button"
                onclick="window.close();"
                id="closeBtn"
                class="btn btn-outline-light rounded-pill px-4 py-2 shadow-sm"
                style="border-color: rgba(255,255,255,0.3);"
            >
                <i class="bi bi-x-lg me-1"></i>
                Close Tab
            </button>

        </div>

    </div>


    <!-- ============================================================
         VALIDATION ERRORS
         ============================================================ -->

    <?php if (session()->getFlashdata('validation_errors')): ?>

        <div class="alert alert-danger py-2 mb-2 small">

            <i class="bi bi-exclamation-triangle-fill me-1"></i>

            <strong>
                Nilai tidak valid, tidak ada yang tersimpan:
            </strong>

            <ul class="mb-0 mt-1">

                <?php foreach (
                    session()->getFlashdata('validation_errors')
                    as $err
                ): ?>

                    <li>
                        <?= esc($err) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>


    <!-- ============================================================
         LOCK STATUS
         ============================================================ -->

    <?php if ($isLocked): ?>

        <div class="alert alert-warning py-1 mb-2 small">

            <i class="bi bi-lock-fill me-1"></i>

            <strong>Locked</strong>
            -
            Scores can no longer be edited.

        </div>

    <?php else: ?>

        <div class="alert alert-info py-1 mb-2 small">

            <i class="bi bi-info-circle me-1"></i>

            Paste directly from Excel into the first cell using
            <strong>Ctrl + V</strong>.

        </div>

    <?php endif; ?>


    <!-- ============================================================
         RELIGION INFORMATION
         ============================================================ -->

    <?php if ($isReligionSubject): ?>

        <div class="alert alert-secondary py-1 mb-2 small">

            <i class="bi bi-info-circle me-1"></i>

            This is a religion subject:
            <strong><?= esc($subjectReligion ?? 'Unknown') ?></strong>.

            Students from other religions are shown in grey
            and cannot be edited.

        </div>

    <?php endif; ?>


    <!-- ============================================================
         FILTER
         ============================================================ -->

    <div class="row g-2 mb-2 align-items-center">

        <div class="col-auto">

            <label class="form-label mb-0 small text-white-50">
                Religion:
            </label>

        </div>


        <div class="col-auto">

            <select
                id="religionFilter"
                class="form-select form-select-sm"
                style="width: 150px;"
            >

                <option value="">
                    All
                </option>

                <?php

                $religions = [];

                foreach ($students as $student) {

                    if (!empty($student['murid_agama'])) {

                        $religion = trim(
                            $student['murid_agama']
                        );

                        if (!in_array($religion, $religions)) {
                            $religions[] = $religion;
                        }
                    }
                }

                sort($religions);

                ?>

                <?php foreach ($religions as $religion): ?>

                    <option
                        value="<?= esc(strtolower($religion)) ?>"
                    >
                        <?= esc($religion) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <div class="col text-end">

            <span class="badge bg-secondary">

                <span id="studentCount">
                    <?= count($students) ?>
                </span>

                Students

            </span>

        </div>

    </div>


    <!-- ============================================================
         FORM
         ============================================================ -->

    <form
        method="post"
        action="<?= base_url('gradebook/save') ?>"
        id="gradebookForm"
    >

        <?= csrf_field() ?>


        <input
            type="hidden"
            name="term_id"
            value="<?= esc($termId) ?>"
        >

        <input
            type="hidden"
            name="class_id"
            value="<?= esc($classId) ?>"
        >

        <input
            type="hidden"
            name="subject_id"
            value="<?= esc($subjectId) ?>"
        >


        <!-- ========================================================
             TABLE
             ======================================================== -->

        <div
            class="table-responsive"
            style="
                border-radius:8px;
                overflow:auto;
                max-height:72vh;
                border:1px solid rgba(255,255,255,0.1);
            "
        >

            <table
                class="table table-sm table-bordered align-middle mb-0"
                id="gradebookTable"
                data-kkm="<?= esc($kkm) ?>"
                style="font-size:0.85rem;"
            >

                <thead
                    class="table-light"
                    style="
                        position:sticky;
                        top:0;
                        z-index:10;
                    "
                >

                    <tr>

                        <th
                            rowspan="2"
                            class="text-center py-1"
                            style="width:40px; min-width:40px;"
                        >
                            No
                        </th>


                        <th
                            rowspan="2"
                            class="py-1"
                            style="
                                min-width:180px;
                                position:sticky;
                                left:0;
                                z-index:11;
                                background:#f8f9fa;
                            "
                        >
                            Student
                        </th>


                        <th
                            rowspan="2"
                            class="text-center py-1"
                            style="
                                min-width:90px;
                                position:sticky;
                                left:180px;
                                z-index:11;
                                background:#f8f9fa;
                            "
                        >
                            Religion
                        </th>


                        <th
                            colspan="2"
                            class="text-center py-1"
                        >
                            Chapter Test 1
                        </th>


                        <th
                            colspan="2"
                            class="text-center py-1"
                        >
                            Chapter Test 2
                        </th>


                        <th
                            rowspan="2"
                            class="text-center py-1"
                            style="
                                min-width:90px;
                                line-height:1.1;
                            "
                        >
                            Indiv.<br>
                            Project
                        </th>


                        <th
                            rowspan="2"
                            class="text-center py-1"
                            style="
                                min-width:90px;
                                line-height:1.1;
                            "
                        >
                            Group<br>
                            Project
                        </th>

                    </tr>


                    <tr>

                        <th
                            class="text-center py-1"
                            style="min-width:70px;"
                        >
                            CT1
                        </th>

                        <th
                            class="text-center py-1"
                            style="min-width:80px;"
                        >
                            Rem.
                        </th>

                        <th
                            class="text-center py-1"
                            style="min-width:70px;"
                        >
                            CT2
                        </th>

                        <th
                            class="text-center py-1"
                            style="min-width:80px;"
                        >
                            Rem.
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php if (!empty($students)): ?>

                    <?php foreach ($students as $index => $student): ?>

                        <?php

                        $studentId =
                            $student['id'];

                        $studentScore =
                            $scores[$studentId] ?? [];

                        $religion =
                            trim(
                                $student['murid_agama'] ?? ''
                            );


                        // =================================================
                        // NORMALIZE STUDENT RELIGION
                        // =================================================

                        $normalizedStudentReligion =
                            strtolower($religion);

                        $normalizedStudentReligion =
                            $religionMap[
                                $normalizedStudentReligion
                            ] ?? null;


                        // =================================================
                        // RELIGION MISMATCH
                        // =================================================

                        $religionMismatch = (
                            $isReligionSubject &&
                            $subjectReligion !== null &&
                            $normalizedStudentReligion !==
                                $subjectReligion
                        );


                        // =================================================
                        // ROW CLASS
                        // =================================================

                        $rowClass = $religionMismatch
                            ? 'student-row religion-disabled'
                            : 'student-row';


                        // =================================================
                        // READONLY
                        // =================================================

                        $readOnly = (
                            $isLocked ||
                            $religionMismatch
                        )
                            ? 'readonly'
                            : '';

                        ?>


                        <tr
                            class="<?= $rowClass ?>"
                            data-religion="<?= esc(
                                strtolower($religion)
                            ) ?>"
                        >

                            <!-- NO -->

                            <td
                                class="text-center text-muted py-1"
                            >
                                <?= $index + 1 ?>
                            </td>


                            <!-- STUDENT -->

                            <td
                                class="py-1 student-name-cell"
                                style="
                                    position:sticky;
                                    left:0;
                                    z-index:5;
                                    white-space:nowrap;
                                    overflow:hidden;
                                    text-overflow:ellipsis;
                                    max-width:180px;
                                "
                            >

                                <input
                                    type="hidden"
                                    name="student_id[]"
                                    value="<?= esc($studentId) ?>"
                                >


                                <div
                                    class="fw-bold text-truncate"
                                    title="<?= esc(
                                        $student['name']
                                    ) ?>"
                                >
                                    <?= esc(
                                        $student['name']
                                    ) ?>
                                </div>

                            </td>


                            <!-- RELIGION -->

                            <td
                                class="text-center py-1 religion-cell"
                                style="
                                    position:sticky;
                                    left:180px;
                                    z-index:5;
                                "
                            >

                                <?php if (!empty($religion)): ?>

                                    <?= esc($religion) ?>

                                <?php else: ?>

                                    <span class="text-muted">
                                        -
                                    </span>

                                <?php endif; ?>

                            </td>


                            <?php

                            $cols = [

                                'ct1' =>
                                    $studentScore['ct1'] ?? null,

                                'ct1_remedial' =>
                                    $studentScore['ct1_remedial'] ?? null,

                                'ct2' =>
                                    $studentScore['ct2'] ?? null,

                                'ct2_remedial' =>
                                    $studentScore['ct2_remedial'] ?? null,

                                'individual_project' =>
                                    $studentScore['individual_project'] ?? null,

                                'group_project' =>
                                    $studentScore['group_project'] ?? null,

                            ];

                            $colIndex = 0;

                            ?>


                            <?php foreach (
                                $cols as $field => $dbValue
                            ): ?>

                                <td class="p-1">

                                    <input
                                        type="text"
                                        inputmode="decimal"
                                        name="<?= $field ?>[<?= $studentId ?>]"
                                        value="<?= esc(
                                            fieldValue(
                                                $oldInput,
                                                $field,
                                                $studentId,
                                                $dbValue
                                            )
                                        ) ?>"
                                        class="form-control form-control-sm px-1 text-center grade-cell"
                                        data-col="<?= $colIndex ?>"
                                        autocomplete="off"
                                        spellcheck="false"
                                        <?= $readOnly ?>
                                    >

                                </td>


                                <?php
                                $colIndex++;
                                ?>

                            <?php endforeach; ?>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="9"
                            class="text-center py-4"
                        >
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

        <div
            class="d-flex justify-content-between align-items-center mt-2"
        >

            <div
                class="text-muted small"
                style="font-size:0.75rem;"
            >

                <?php if ($isLocked): ?>

                    <i class="bi bi-lock-fill me-1"></i>
                    Read-only mode.

                <?php else: ?>

                    <i class="bi bi-keyboard me-1"></i>
                    Valid scores: 0-100.

                <?php endif; ?>

            </div>


            <div>

                <?php if (!$isLocked): ?>

                    <button
                        type="submit"
                        id="saveBtn"
                        class="btn btn-sm btn-primary rounded-pill px-4"
                    >
                        <i class="bi bi-save me-1"></i>
                        Save
                    </button>

                <?php else: ?>

                    <span
                        class="btn btn-sm btn-warning rounded-pill px-4"
                    >
                        <i class="bi bi-lock-fill me-1"></i>
                        Locked
                    </span>

                <?php endif; ?>

            </div>

        </div>

    </form>

</div>


<?= $this->endSection() ?>


<?= $this->section('script') ?>

<script>

$(document).ready(function () {

    const isLocked =
        <?= $isLocked ? 'true' : 'false' ?>;

    const kkm =
        parseFloat(
            $('#gradebookTable').data('kkm')
        ) || 75;


    let formDirty = false;


    // ============================================================
    // RELIGION FILTER
    // ============================================================

    $('#religionFilter').on('change', function () {

        const selectedReligion =
            $(this).val().toLowerCase();

        let visibleCount = 0;


        $('.student-row').each(function () {

            const rowReligion =
                ($(this).data('religion') || '')
                .toString()
                .toLowerCase();


            if (
                selectedReligion === '' ||
                rowReligion === selectedReligion
            ) {

                $(this).show();

                visibleCount++;

            } else {

                $(this).hide();

            }

        });


        $('#studentCount').text(
            visibleCount
        );

    });


    // ============================================================
    // VALIDATION
    // ============================================================

    function validateAndFormatCell($cell) {

        let value =
            $cell.val().trim();


        if (value === '-' || value === '') {

            $cell
                .val('')
                .removeClass(
                    'is-invalid text-danger fw-bold'
                );

            return true;
        }


        value =
            value.replace(',', '.');

        $cell.val(value);


        let number =
            parseFloat(value);


        if (
            isNaN(number) ||
            number < 0 ||
            number > 100
        ) {

            $cell
                .addClass('is-invalid')
                .removeClass(
                    'text-danger fw-bold'
                );

            return false;
        }


        $cell.removeClass(
            'is-invalid'
        );


        if (number < kkm) {

            $cell.addClass(
                'text-danger fw-bold'
            );

        } else {

            $cell.removeClass(
                'text-danger fw-bold'
            );

        }


        return true;
    }


    $('.grade-cell').each(function () {

        validateAndFormatCell(
            $(this)
        );

    });


    // ============================================================
    // EDITING
    // ============================================================

    if (!isLocked) {


        // ========================================================
        // DIRTY STATE
        // ========================================================

        $('.grade-cell:not([readonly])').on(
            'input',
            function () {

                validateAndFormatCell(
                    $(this)
                );

                formDirty = true;

            }
        );


        window.addEventListener(
            'beforeunload',
            function (e) {

                if (formDirty) {

                    e.preventDefault();

                    e.returnValue = '';

                }

            }
        );


        $('#backBtn, #cancelBtn').on(
            'click',
            function (e) {

                if (formDirty) {

                    const proceed =
                        confirm(
                            'Ada perubahan yang belum disimpan. Yakin ingin keluar?'
                        );

                    if (!proceed) {

                        e.preventDefault();

                    }

                }

            }
        );


        // ========================================================
        // KEYBOARD NAVIGATION
        // ========================================================

        $('.grade-cell:not([readonly])').on(
            'keydown',
            function (e) {

                const col =
                    parseInt(
                        $(this).data('col')
                    );

                const $currentRow =
                    $(this).closest('tr');

                let target = null;


                if (
                    e.key === 'Tab' &&
                    !e.shiftKey
                ) {

                    e.preventDefault();


                    target =
                        $currentRow.find(
                            '.grade-cell:not([readonly])[data-col="' +
                            (col + 1) +
                            '"]'
                        );


                    if (!target.length) {

                        target =
                            $currentRow
                                .nextAll(':visible')
                                .first()
                                .find(
                                    '.grade-cell:not([readonly])[data-col="0"]'
                                );

                    }

                }


                else if (
                    e.key === 'Tab' &&
                    e.shiftKey
                ) {

                    e.preventDefault();


                    target =
                        $currentRow.find(
                            '.grade-cell:not([readonly])[data-col="' +
                            (col - 1) +
                            '"]'
                        );


                    if (!target.length) {

                        target =
                            $currentRow
                                .prevAll(':visible')
                                .first()
                                .find(
                                    '.grade-cell:not([readonly])[data-col="5"]'
                                );

                    }

                }


                else if (
                    e.key === 'Enter' ||
                    e.key === 'ArrowDown'
                ) {

                    e.preventDefault();


                    target =
                        $currentRow
                            .nextAll(':visible')
                            .first()
                            .find(
                                '.grade-cell:not([readonly])[data-col="' +
                                col +
                                '"]'
                            );

                }


                else if (
                    e.key === 'ArrowUp'
                ) {

                    e.preventDefault();


                    target =
                        $currentRow
                            .prevAll(':visible')
                            .first()
                            .find(
                                '.grade-cell:not([readonly])[data-col="' +
                                col +
                                '"]'
                            );

                }


                else if (
                    e.key === 'ArrowRight' &&
                    this.selectionStart ===
                    this.value.length
                ) {

                    target =
                        $currentRow.find(
                            '.grade-cell:not([readonly])[data-col="' +
                            (col + 1) +
                            '"]'
                        );

                }


                else if (
                    e.key === 'ArrowLeft' &&
                    this.selectionStart === 0
                ) {

                    target =
                        $currentRow.find(
                            '.grade-cell:not([readonly])[data-col="' +
                            (col - 1) +
                            '"]'
                        );

                }


                if (
                    target &&
                    target.length
                ) {

                    target.focus();

                    target[0].select();

                }

            }
        );


        // ========================================================
        // PASTE FROM EXCEL
        // ========================================================

        $('.grade-cell:not([readonly])').on(
            'paste',
            function (e) {

                e.preventDefault();


                const text =
                    (
                        e.originalEvent
                            .clipboardData ||
                        window.clipboardData
                    )
                    .getData('text');


                if (!text) {
                    return;
                }


                // ------------------------------------------------
                // Do not allow paste when religion filter active
                // ------------------------------------------------

                const activeFilter =
                    $('#religionFilter').val();


                if (activeFilter !== '') {

                    const proceed =
                        confirm(
                            'Filter Religion sedang aktif ("' +
                            $('#religionFilter option:selected').text() +
                            '"). Paste akan melompati baris yang tersembunyi dan bisa salah menempatkan nilai.\n\n' +
                            'Reset filter dan lanjutkan paste?'
                        );


                    if (!proceed) {
                        return;
                    }


                    $('#religionFilter')
                        .val('')
                        .trigger('change');

                }


                const rows =
                    text
                        .replace(/\r\n/g, '\n')
                        .replace(/\r/g, '\n')
                        .split('\n')
                        .filter(
                            (r, i, arr) =>
                                !(
                                    r === '' &&
                                    i === arr.length - 1
                                )
                        );


                const startCol =
                    parseInt(
                        $(this).data('col')
                    );


                const totalCols = 6;


                // ------------------------------------------------
                // Column mismatch warning
                // ------------------------------------------------

                const firstRowCols =
                    rows[0]
                        ? rows[0].split('\t').length
                        : 0;


                if (
                    firstRowCols >
                    (totalCols - startCol)
                ) {

                    const proceed =
                        confirm(
                            'Data yang di-paste punya ' +
                            firstRowCols +
                            ' kolom, tapi hanya ' +
                            (totalCols - startCol) +
                            ' kolom nilai tersedia mulai dari sel ini.\n\n' +
                            'Kolom berlebih akan diabaikan. Lanjutkan?'
                        );


                    if (!proceed) {
                        return;
                    }

                }


                let $currentRow =
                    $(this).closest('tr');


                rows.forEach(function (rowData) {

                    if (!$currentRow.length) {
                        return;
                    }


                    // Skip religion-disabled rows

                    while (
                        $currentRow.length &&
                        $currentRow.hasClass(
                            'religion-disabled'
                        )
                    ) {

                        $currentRow =
                            $currentRow
                                .nextAll(':visible')
                                .first();

                    }


                    if (!$currentRow.length) {
                        return;
                    }


                    const columns =
                        rowData.split('\t');


                    columns.forEach(
                        function (
                            value,
                            colIndex
                        ) {

                            const target =
                                $currentRow.find(
                                    '.grade-cell:not([readonly])[data-col="' +
                                    (
                                        startCol +
                                        colIndex
                                    ) +
                                    '"]'
                                );


                            if (target.length) {

                                target.val(
                                    value.trim()
                                );


                                validateAndFormatCell(
                                    target
                                );


                                formDirty = true;

                            }

                        }
                    );


                    $currentRow =
                        $currentRow
                            .nextAll(':visible')
                            .first();

                });


                $(this).focus();

            }
        );


        // ========================================================
        // SUBMIT
        // ========================================================

        $('#gradebookForm').on(
            'submit',
            function (e) {

                let invalid = false;


                $('.grade-cell:visible:not([readonly])')
                    .each(function () {

                        if (
                            !validateAndFormatCell(
                                $(this)
                            )
                        ) {

                            invalid = true;

                        }

                    });


                if (invalid) {

                    e.preventDefault();


                    alert(
                        'Some scores are invalid. Scores must be between 0 and 100.'
                    );


                    $(
                        '.grade-cell.is-invalid:visible:not([readonly])'
                    )
                    .first()
                    .focus();


                    return false;

                }


                formDirty = false;


                $('#saveBtn')
                    .prop('disabled', true)
                    .html(
                        '<span class="spinner-border spinner-border-sm me-1"></span> Saving...'
                    );

            }
        );

    }

});

</script>


<?php if (session()->getFlashdata('success')): ?>

<script>

Swal.fire({

    icon: 'success',

    title: 'Success',

    text:
        '<?= esc(
            session()->getFlashdata('success')
        ) ?>',

    timer: 2000,

    showConfirmButton: false

});

</script>

<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>

<script>

Swal.fire({

    icon: 'error',

    title: 'Error',

    text:
        '<?= esc(
            session()->getFlashdata('error')
        ) ?>'

});

</script>

<?php endif; ?>


<?= $this->endSection() ?>