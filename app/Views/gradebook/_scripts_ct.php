<?php /** Expected vars: $isLocked, $kkm */
$isLocked = $isLocked ?? false;
$kkm      = $kkm ?? 75;
?>
<script>
$(document).ready(function () {

    const isLocked = <?= $isLocked ? 'true' : 'false' ?>;
    const kkm = parseFloat($('#gradebookTable').data('kkm')) || <?= (float) $kkm ?>;

    let formDirty = false;

    // ============================================================
    // RELIGION FILTER (CT)
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
    // VALIDATION
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

    $('.grade-cell').each(function () {
        validateAndFormatCell($(this));
    });

    // ============================================================
    // EDITING
    // ============================================================

    if (!isLocked) {

        $('.grade-cell:not([readonly])').on('input', function () {
            validateAndFormatCell($(this));
            formDirty = true;
        });

        window.addEventListener('beforeunload', function (e) {
            if (formDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        $('#backBtn, #cancelBtn').on('click', function (e) {
            if (formDirty) {
                const proceed = confirm('Ada perubahan yang belum disimpan. Yakin ingin keluar?');
                if (!proceed) {
                    e.preventDefault();
                }
            }
        });

        // ========================================================
        // KEYBOARD NAVIGATION
        // ========================================================

        $('.grade-cell:not([readonly])').on('keydown', function (e) {

            const col = parseInt($(this).data('col'));
            const $currentRow = $(this).closest('tr');
            let target = null;

            if (e.key === 'Tab' && !e.shiftKey) {

                e.preventDefault();

                target = $currentRow.find('.grade-cell:not([readonly])[data-col="' + (col + 1) + '"]');

                if (!target.length) {
                    target = $currentRow.nextAll(':visible').first()
                        .find('.grade-cell:not([readonly])[data-col="0"]');
                }

            } else if (e.key === 'Tab' && e.shiftKey) {

                e.preventDefault();

                target = $currentRow.find('.grade-cell:not([readonly])[data-col="' + (col - 1) + '"]');

                if (!target.length) {
                    target = $currentRow.prevAll(':visible').first()
                        .find('.grade-cell:not([readonly])[data-col="5"]');
                }

            } else if (e.key === 'Enter' || e.key === 'ArrowDown') {

                e.preventDefault();

                target = $currentRow.nextAll(':visible').first()
                    .find('.grade-cell:not([readonly])[data-col="' + col + '"]');

            } else if (e.key === 'ArrowUp') {

                e.preventDefault();

                target = $currentRow.prevAll(':visible').first()
                    .find('.grade-cell:not([readonly])[data-col="' + col + '"]');

            } else if (e.key === 'ArrowRight' && this.selectionStart === this.value.length) {

                target = $currentRow.find('.grade-cell:not([readonly])[data-col="' + (col + 1) + '"]');

            } else if (e.key === 'ArrowLeft' && this.selectionStart === 0) {

                target = $currentRow.find('.grade-cell:not([readonly])[data-col="' + (col - 1) + '"]');
            }

            if (target && target.length) {
                target.focus();
                target[0].select();
            }
        });

        // ========================================================
        // PASTE FROM EXCEL
        // ========================================================

        $('.grade-cell:not([readonly])').on('paste', function (e) {

            e.preventDefault();

            const text = (e.originalEvent.clipboardData || window.clipboardData).getData('text');

            if (!text) {
                return;
            }

            // Do not allow paste when religion filter active
            const activeFilter = $('#religionFilter').val();

            if (activeFilter !== '') {

                const proceed = confirm(
                    'Filter Religion sedang aktif ("' +
                    $('#religionFilter option:selected').text() +
                    '"). Paste akan melompati baris yang tersembunyi dan bisa salah menempatkan nilai.\n\n' +
                    'Reset filter dan lanjutkan paste?'
                );

                if (!proceed) {
                    return;
                }

                $('#religionFilter').val('').trigger('change');
            }

            const rows = text
                .replace(/\r\n/g, '\n')
                .replace(/\r/g, '\n')
                .split('\n')
                .filter((r, i, arr) => !(r === '' && i === arr.length - 1));

            const startCol = parseInt($(this).data('col'));
            const totalCols = 6;

            const firstRowCols = rows[0] ? rows[0].split('\t').length : 0;

            if (firstRowCols > (totalCols - startCol)) {

                const proceed = confirm(
                    'Data yang di-paste punya ' + firstRowCols +
                    ' kolom, tapi hanya ' + (totalCols - startCol) +
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
                        '.grade-cell:not([readonly])[data-col="' + (startCol + colIndex) + '"]'
                    );

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

        // ========================================================
        // SUBMIT
        // ========================================================

        $('#gradebookForm').on('submit', function (e) {

            let invalid = false;

            $('.grade-cell:visible:not([readonly])').each(function () {
                if (!validateAndFormatCell($(this))) {
                    invalid = true;
                }
            });

            if (invalid) {
                e.preventDefault();

                alert('Some scores are invalid. Scores must be between 0 and 100.');

                $('.grade-cell.is-invalid:visible:not([readonly])').first().focus();

                return false;
            }

            formDirty = false;

            $('#saveBtn')
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
        });
    }

});
</script>
