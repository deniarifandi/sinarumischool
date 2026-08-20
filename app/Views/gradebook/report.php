<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<style>

    .report-filter {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .report-filter::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(59,130,246,.7),
            transparent
        );
    }

    .custom-select-dark {
        background-color: rgba(0,0,0,.25);
        border: 1px solid rgba(255,255,255,.1);
        color: #e2e8f0;
        border-radius: 10px;
        padding: .65rem 1rem;
    }

    .custom-select-dark:focus {
        background-color: rgba(0,0,0,.4);
        color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 .25rem rgba(59,130,246,.2);
    }

    .custom-select-dark option {
        background: #1e293b;
        color: #fff;
    }

    .student-card {
        display: flex;
        align-items: center;
        gap: 15px;

        padding: 15px 18px;

        border-radius: 14px;

        background:
            linear-gradient(
                145deg,
                rgba(255,255,255,.045),
                rgba(255,255,255,.015)
            );

        border: 1px solid rgba(255,255,255,.08);

        transition: all .25s ease;
    }

    .student-card:hover {
        transform: translateY(-2px);
        border-color: rgba(59,130,246,.5);
        box-shadow: 0 8px 25px rgba(0,0,0,.15);
    }

    .student-number {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: rgba(59,130,246,.12);
        color: #60a5fa;

        font-weight: 700;
        font-size: .85rem;
    }

    .student-avatar {
        width: 42px;
        height: 42px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: rgba(255,255,255,.07);

        color: rgba(255,255,255,.6);

        font-size: 1.1rem;
    }

    .student-name {
        color: #f1f5f9;
        font-weight: 700;
    }

    .student-meta {
        font-size: .75rem;
        color: rgba(255,255,255,.4);
    }

    .student-actions {
        margin-left: auto;
    }

    .empty-state {
        border: 1.5px dashed rgba(255,255,255,.15);
        border-radius: 16px;
        padding: 3rem 1.5rem;
        text-align: center;
        color: rgba(255,255,255,.45);
    }

    .empty-state i {
        font-size: 2.5rem;
        display: block;
        margin-bottom: .75rem;
    }

</style>


<div class="container-fluid px-0 py-3">

    <!-- HEADER -->

    <div class="mb-4">

        <h5 class="fw-bold text-light">

            <i class="bi bi-file-earmark-person-fill me-2 text-primary"></i>

            Student Report Card

        </h5>

        <p class="text-white-50 mb-0">

            Select the academic period and class to view student report cards.

        </p>

    </div>


    <!-- FILTER -->

    <div class="report-filter p-4 mb-4">

        <div class="d-flex align-items-center mb-3">

            <i class="bi bi-funnel-fill text-primary me-2"></i>

            <h6 class="mb-0 text-light fw-bold">

                Select Class

            </h6>

        </div>


        <form method="get"
              action="<?= base_url('gradebook/report') ?>">

            <div class="row g-3 align-items-end">


                <!-- Academic Year -->

                <div class="col-md-4">

                    <label class="form-label text-white-50 small">

                        Academic Year

                    </label>

                    <select
                        name="academic_year_id"
                        id="academicYear"
                        class="form-select custom-select-dark"
                        required
                    >

                        <option value="">
                            -- Select Academic Year --
                        </option>

                        <?php foreach ($academicYears as $ay): ?>

                            <option
                                value="<?= esc($ay['id']) ?>"
                                <?= $selectedAcademicYearId == $ay['id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= esc($ay['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Term -->

                <div class="col-md-4">

                    <label class="form-label text-white-50 small">

                        Term

                    </label>

                    <select
                        name="term_id"
                        id="term"
                        class="form-select custom-select-dark"
                        required
                    >

                        <option value="">
                            -- Select Term --
                        </option>

                        <?php foreach ($terms as $term): ?>

                            <option
                                value="<?= esc($term['id']) ?>"
                                data-ay="<?= esc($term['academic_year_id']) ?>"
                                <?= $selectedTermId == $term['id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= esc($term['semester_name']) ?>
                                -
                                <?= esc($term['name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Class -->

                <div class="col-md-4">

                    <label class="form-label text-white-50 small">

                        Class

                    </label>

                    <select
                        name="class_id"
                        class="form-select custom-select-dark"
                        required
                    >

                        <option value="">
                            -- Select Class --
                        </option>

                        <?php foreach ($classes as $class): ?>

                            <option
                                value="<?= esc($class['id']) ?>"
                                <?= $selectedClassId == $class['id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= esc($class['grade_name']) ?>
                                -
                                <?= esc($class['class_name']) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="col-12">

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                    >

                        <i class="bi bi-search me-1"></i>

                        View Students

                    </button>

                </div>

            </div>

        </form>

    </div>


    <?php if ($selectedClassId && $selectedTermId): ?>


        <!-- STUDENT HEADER -->

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h6 class="text-light fw-bold mb-1">

                    Students

                </h6>

                <div class="text-white-50 small">

                    <?= count($students) ?> students found

                </div>

            </div>


            <?php if (!empty($students)): ?>

                <button
                    type="button"
                    class="btn btn-outline-light btn-sm rounded-pill px-3"
                    onclick="printAll()"
                >

                    <i class="bi bi-printer me-1"></i>

                    Print All

                </button>

            <?php endif; ?>

        </div>


        <!-- STUDENT LIST -->

        <?php if (!empty($students)): ?>

            <div class="row g-3">

                <?php foreach ($students as $index => $student): ?>

                    <div class="col-12">

                        <div class="student-card">


                            <!-- NUMBER -->

                            <div class="student-number">

                                <?= $index + 1 ?>

                            </div>


                            <!-- AVATAR -->

                            <div class="student-avatar">

                                <i class="bi bi-person-fill"></i>

                            </div>


                            <!-- INFO -->

                            <div>

                                <div class="student-name">

                                    <?= esc($student['name']) ?>

                                </div>

                                <div class="student-meta">

                                    <?= esc($student['murid_agama'] ?? '') ?>

                                </div>

                            </div>


                            <!-- ACTION -->

                            <div class="student-actions">

                                <a
                                    href="<?= base_url('gradebook/student-report') ?>?<?= http_build_query([
                                        'student_id'      => $student['id'],
                                        'class_id'        => $selectedClassId,
                                        'academic_year_id' => $selectedAcademicYearId,
                                        'term_id'         => $selectedTermId,
                                    ]) ?>"
                                    class="btn btn-outline-info btn-sm rounded-pill px-3"
                                >

                                    <i class="bi bi-eye me-1"></i>

                                    View

                                </a>

                                <a
                                    href="<?= base_url('gradebook/student-report/print') ?>?<?= http_build_query([
                                        'student_id'      => $student['id'],
                                        'class_id'        => $selectedClassId,
                                        'academic_year_id' => $selectedAcademicYearId,
                                        'term_id'         => $selectedTermId,
                                    ]) ?>"
                                    target="_blank"
                                    class="btn btn-primary btn-sm rounded-pill px-3"
                                >

                                    <i class="bi bi-printer me-1"></i>

                                    Print

                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state">

                <i class="bi bi-people"></i>

                <div class="fw-semibold text-white-50">

                    No students found

                </div>

                <div class="small">

                    There are no students assigned to this class.

                </div>

            </div>

        <?php endif; ?>


    <?php else: ?>


        <!-- INITIAL EMPTY STATE -->

        <div class="empty-state">

            <i class="bi bi-people"></i>

            <div class="fw-semibold text-white-50">

                Select Academic Year, Term and Class

            </div>

            <div class="small">

                The students will appear here.

            </div>

        </div>


    <?php endif; ?>


</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>

<script>

$(document).ready(function () {

    function filterTerms() {

        const ay = $('#academicYear').val();

        $('#term option[data-ay]').each(function () {

            $(this).toggle(
                $(this).data('ay') == ay
            );

        });

        const selected = $('#term').val();

        const selectedOption =
            $('#term option[value="' + selected + '"]');

        if (
            selected &&
            selectedOption.data('ay') != ay
        ) {
            $('#term').val('');
        }

    }


    $('#academicYear').on('change', filterTerms);

    filterTerms();

});


function printAll()
{
    alert(
        'The Print All function will be connected to the class report printer next.'
    );
}

</script>

<?= $this->endSection() ?>