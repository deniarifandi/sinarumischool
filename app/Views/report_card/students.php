<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    .report-header {
        background: linear-gradient(
            135deg,
            rgba(59, 130, 246, 0.12),
            rgba(59, 130, 246, 0.02)
        );
        border: 1px solid rgba(59, 130, 246, 0.25);
        border-radius: 16px;
        padding: 20px 24px;
    }

    .student-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 18px;
        border-radius: 14px;
        background: rgba(255,255,255,0.035);
        border: 1px solid rgba(255,255,255,0.08);
        transition: all .2s ease;
    }

    .student-card:hover {
        transform: translateY(-2px);
        border-color: rgba(59,130,246,.5);
        background: rgba(255,255,255,.06);
    }

    .student-number {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(59,130,246,.15);
        color: #60a5fa;
        font-weight: 700;
    }

    .student-name {
        font-weight: 700;
        color: #f1f5f9;
    }

    .student-meta {
        font-size: .75rem;
        color: rgba(255,255,255,.4);
    }

    .filter-card {
        background: rgba(255,255,255,.025);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 16px;
    }

    .custom-select-dark {
        background-color: rgba(0,0,0,.25);
        border: 1px solid rgba(255,255,255,.1);
        color: #e2e8f0;
        border-radius: 10px;
    }

    .custom-select-dark:focus {
        background-color: rgba(0,0,0,.4);
        border-color: #3b82f6;
        color: #fff;
        box-shadow: 0 0 0 .2rem rgba(59,130,246,.2);
    }

    .custom-select-dark option {
        background: #1e293b;
        color: #fff;
    }

    .empty-state {
        border: 1.5px dashed rgba(255,255,255,.12);
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        color: rgba(255,255,255,.45);
    }
</style>

<div class="container-fluid px-0 py-3">

    <!-- HEADER -->
    <div class="report-header mb-4">

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-file-earmark-person-fill text-primary fs-4 me-2"></i>

                    <h5 class="fw-bold text-light mb-0">
                        Student Report Card
                    </h5>
                </div>

                <div class="text-white-50 small">
                    Select an academic period and student to view or print the report card.
                </div>
            </div>

            <a href="<?= base_url() ?>"
               class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

        </div>

        <!-- CLASS INFO -->
        <div class="d-flex flex-wrap gap-2 mt-3">

            <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary border border-primary px-3 py-2">
                <i class="bi bi-door-open me-1"></i>
                <?= esc($class['class_name'] ?? '-') ?>
            </span>

            <span class="badge rounded-pill bg-secondary bg-opacity-25 text-light border border-secondary px-3 py-2">
                <i class="bi bi-mortarboard-fill me-1"></i>
                <?= esc($class['grade_name'] ?? '-') ?>
            </span>

            <span class="badge rounded-pill bg-success bg-opacity-25 text-success border border-success px-3 py-2">
                <i class="bi bi-people-fill me-1"></i>
                <?= count($students) ?> Students
            </span>

        </div>

    </div>


    <!-- FILTER -->
    <div class="filter-card p-4 mb-4">

        <div class="d-flex align-items-center mb-3">

            <i class="bi bi-funnel-fill text-primary me-2"></i>

            <h6 class="text-light fw-bold mb-0">
                Report Period
            </h6>

        </div>

        <div class="row g-3">

            <!-- Academic Year -->
            <div class="col-md-6">

                <label class="form-label text-white-50 small">
                    Academic Year
                </label>

                <select id="academicYear"
                        class="form-select custom-select-dark">

                    <option value="">
                        -- Select Academic Year --
                    </option>

                    <?php foreach (($academicYears ?? []) as $year): ?>

                        <option value="<?= esc($year['id']) ?>">
                            <?= esc($year['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- Term -->
            <div class="col-md-6">

                <label class="form-label text-white-50 small">
                    Term
                </label>

                <select id="term"
                        class="form-select custom-select-dark"
                        disabled>

                    <option value="">
                        -- Select Academic Year First --
                    </option>

                    <?php foreach (($terms ?? []) as $term): ?>

                        <option
                            value="<?= esc($term['id']) ?>"
                            data-ay="<?= esc($term['academic_year_id']) ?>"
                        >
                            <?= esc($term['semester_name'] ?? '') ?>
                            -
                            <?= esc($term['name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

    </div>


    <!-- STUDENT LIST -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h6 class="text-light fw-bold mb-1">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Students
            </h6>

            <div class="small text-white-50">
                Select a student to view their complete grade report.
            </div>
        </div>

        <span class="badge bg-secondary">
            <?= count($students) ?> Students
        </span>

    </div>


    <?php if (!empty($students)): ?>

        <div class="row g-3">

            <?php foreach ($students as $index => $student): ?>

                <div class="col-12 col-md-6 col-xl-4">

                    <div class="student-card">

                        <div class="d-flex align-items-center gap-3">

                            <div class="student-number">
                                <?= $index + 1 ?>
                            </div>

                            <div>

                                <div class="student-name">
                                    <?= esc($student['name']) ?>
                                </div>

                                <div class="student-meta">
                                    Student ID:
                                    <?= esc($student['id']) ?>
                                </div>

                            </div>

                        </div>


                        <a href="#"
                           class="btn btn-sm btn-outline-primary rounded-pill view-report"
                           data-student="<?= esc($student['id']) ?>" target="_blank">

                            <i class="bi bi-eye me-1"></i>
                            View

                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty-state">

            <i class="bi bi-person-x fs-1 d-block mb-3"></i>

            <div class="fw-semibold text-white-50">
                No students found
            </div>

            <div class="small mt-1">
                There are no active students assigned to this class.
            </div>

        </div>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>

<script>

$(document).ready(function () {

    const baseReportUrl =
        "<?= base_url('report-card/student') ?>";


    // ============================================================
    // Academic Year → Term
    // ============================================================

    function refreshTerms() {

        const ay = $('#academicYear').val();
        const $term = $('#term');

        $term.find('option[data-ay]').each(function () {

            $(this).toggle(
                $(this).data('ay') == ay
            );

        });

        if (ay) {

            $term.prop('disabled', false);

            $term.find('option:first')
                .text('-- Select Term --');

        } else {

            $term.prop('disabled', true);
            $term.val('');

            $term.find('option:first')
                .text('-- Select Academic Year First --');
        }

        updateLinks();
    }


    // ============================================================
    // Build Student Report URLs
    // ============================================================

    function updateLinks() {

        const ay   = $('#academicYear').val();
        const term = $('#term').val();

        $('.view-report').each(function () {

            const studentId = $(this).data('student');

            if (ay && term) {

                const url =
                    baseReportUrl +
                    '/' + encodeURIComponent(studentId) +
                    '?class_id=<?= esc($class['id']) ?>' +
                    '&academic_year_id=' + encodeURIComponent(ay) +
                    '&term_id=' + encodeURIComponent(term);

                $(this)
                    .attr('href', url)
                    .removeClass('disabled');

            } else {

                $(this)
                    .attr('href', '#')
                    .addClass('disabled');

            }

        });

    }


    $('#academicYear').on('change', function () {

        $('#term').val('');

        refreshTerms();

    });


    $('#term').on('change', function () {

        updateLinks();

    });


    // Prevent opening report before period is selected

    $('.view-report').on('click', function (e) {

        if ($(this).hasClass('disabled')) {

            e.preventDefault();

            Swal.fire({
                icon: 'info',
                title: 'Select Report Period',
                text: 'Please select Academic Year and Term first.'
            });

        }

    });


    refreshTerms();

});

</script>

<?= $this->endSection() ?>