<?php /** Expected vars: $isLocked, $kkm, $objectives */
$isLocked   = $isLocked   ?? false;
$kkm        = $kkm        ?? 75;
$objectives = $objectives ?? [];
?>
<script>
$(document).ready(function () {

    // ============================================================
    // VALIDATION score objektif 0-100
    // ============================================================

    const kkm = parseFloat($('#objTable').data('kkm')) || <?= (float) $kkm ?>;

    // Jumlah kolom objective (dinamis, sesuai term)
    const totalObjCols = <?= count($objectives) ?>;

    function validateObjCell($cell) {
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

    $('.obj-cell').each(function () {
        validateObjCell($(this));
    });

    const isLocked = <?= $isLocked ? 'true' : 'false' ?>;

    // ============================================================
    // RELIGION FILTER (OBJECTIVE TAB)
    // ============================================================

    $('#objReligionFilter').on('change', function () {

        const selectedReligion = $(this).val().toLowerCase();
        let visibleCount = 0;

        $('.obj-student-row').each(function () {
            const rowReligion = ($(this).data('religion') || '').toString().toLowerCase();

            if (selectedReligion === '' || rowReligion === selectedReligion) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        $('#objStudentCount').text(visibleCount);
    });

    if (!isLocked) {

        let formDirtyObj = false;

        $('.obj-cell:not([readonly])').on('input', function () {
            validateObjCell($(this));
            formDirtyObj = true;
        });

        window.addEventListener('beforeunload', function (e) {
            if (formDirtyObj) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // ========================================================
        // KEYBOARD NAVIGATION (OBJECTIVE)
        // ========================================================

        $('.obj-cell:not([readonly])').on('keydown', function (e) {

            const col = parseInt($(this).data('col'));
            const $currentRow = $(this).closest('tr');
            let target = null;

            if (e.key === 'Tab' && !e.shiftKey) {

                e.preventDefault();

                target = $currentRow.find('.obj-cell:not([readonly])[data-col="' + (col + 1) + '"]');

                if (!target.length) {
                    target = $currentRow.nextAll(':visible').first()
                        .find('.obj-cell:not([readonly])[data-col="0"]');
                }

            } else if (e.key === 'Tab' && e.shiftKey) {

                e.preventDefault();

                target = $currentRow.find('.obj-cell:not([readonly])[data-col="' + (col - 1) + '"]');

                if (!target.length) {
                    target = $currentRow.prevAll(':visible').first()
                        .find('.obj-cell:not([readonly])[data-col="' + (totalObjCols - 1) + '"]');
                }

            } else if (e.key === 'Enter' || e.key === 'ArrowDown') {

                e.preventDefault();

                target = $currentRow.nextAll(':visible').first()
                    .find('.obj-cell:not([readonly])[data-col="' + col + '"]');

            } else if (e.key === 'ArrowUp') {

                e.preventDefault();

                target = $currentRow.prevAll(':visible').first()
                    .find('.obj-cell:not([readonly])[data-col="' + col + '"]');

            } else if (e.key === 'ArrowRight' && this.selectionStart === this.value.length) {

                target = $currentRow.find('.obj-cell:not([readonly])[data-col="' + (col + 1) + '"]');

            } else if (e.key === 'ArrowLeft' && this.selectionStart === 0) {

                target = $currentRow.find('.obj-cell:not([readonly])[data-col="' + (col - 1) + '"]');
            }

            if (target && target.length) {
                target.focus();
                target[0].select();
            }
        });

        // ========================================================
        // PASTE FROM EXCEL (OBJECTIVE)
        // ========================================================

        $('.obj-cell:not([readonly])').on('paste', function (e) {

            e.preventDefault();

            const text = (e.originalEvent.clipboardData || window.clipboardData).getData('text');

            if (!text) {
                return;
            }

            const activeFilter = $('#objReligionFilter').val();

            if (activeFilter !== '') {

                const proceed = confirm(
                    'Filter Religion sedang aktif ("' +
                    $('#objReligionFilter option:selected').text() +
                    '"). Paste akan melompati baris yang tersembunyi dan bisa salah menempatkan nilai.\n\n' +
                    'Reset filter dan lanjutkan paste?'
                );

                if (!proceed) {
                    return;
                }

                $('#objReligionFilter').val('').trigger('change');
            }

            const rows = text
                .replace(/\r\n/g, '\n')
                .replace(/\r/g, '\n')
                .split('\n')
                .filter((r, i, arr) => !(r === '' && i === arr.length - 1));

            const startCol = parseInt($(this).data('col'));

            const firstRowCols = rows[0] ? rows[0].split('\t').length : 0;

            if (firstRowCols > (totalObjCols - startCol)) {

                const proceed = confirm(
                    'Data yang di-paste punya ' + firstRowCols +
                    ' kolom, tapi hanya ' + (totalObjCols - startCol) +
                    ' kolom nilai tersedia mulai dari sel ini.\n\n' +
                    'Kolom berlebih akan diabaikan. Lanjutkan?'
                );

                if (!proceed) {
                    return;
                }
            }

            let $currentRow = $(this).closest('tr');

            rows.forEach(function (rowData) {

                if (!$currentRow.length) {
                    return;
                }

                while ($currentRow.length && $currentRow.hasClass('religion-disabled')) {
                    $currentRow = $currentRow.nextAll(':visible').first();
                }

                if (!$currentRow.length) {
                    return;
                }

                const columns = rowData.split('\t');

                columns.forEach(function (value, colIndex) {

                    const target = $currentRow.find(
                        '.obj-cell:not([readonly])[data-col="' + (startCol + colIndex) + '"]'
                    );

                    if (target.length) {
                        target.val(value.trim());
                        validateObjCell(target);
                        formDirtyObj = true;
                    }
                });

                $currentRow = $currentRow.nextAll(':visible').first();
            });

            $(this).focus();
        });

        // ========================================================
        // SUBMIT
        // ========================================================

        $('#objForm').on('submit', function (e) {

            let invalid = false;

            $('.obj-cell:visible:not([readonly])').each(function () {
                if (!validateObjCell($(this))) {
                    invalid = true;
                }
            });

            if (invalid) {
                e.preventDefault();

                alert('Some objective scores are invalid. Scores must be between 0 and 100.');

                $('.obj-cell.is-invalid:visible').first().focus();

                return false;
            }

            formDirtyObj = false;

            $('#objSaveBtn')
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
        });
    }
});
</script>
