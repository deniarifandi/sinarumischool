<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-light">Gradebook</h4>
            <div class="text-white-50 small">
                Manage student grades by academic year, term, class, and subject.
            </div>
        </div>
    </div>


    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('info')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <!-- Filter Card -->
    <div class="card bg-dark border-secondary shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3 align-items-end">

                <!-- Academic Year -->
                <div class="col-md-5 col-lg-4">

                    <label class="form-label text-white-50 small">
                        Academic Year
                    </label>

                    <select
                        id="academicYearSelect"
                        class="form-select bg-dark text-light border-secondary"
                    >

                        <?php foreach ($academicYears as $year): ?>

                            <option
                                value="<?= esc($year['id']) ?>"
                                <?= (int)$year['id'] === (int)$academicYearId ? 'selected' : '' ?>
                            >
                                <?= esc($year['name']) ?>

                                <?php if ((int)$year['is_active'] === 1): ?>
                                    (Active)
                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Class -->
                <div class="col-md-5 col-lg-4">

                    <label class="form-label text-white-50 small">
                        Class
                    </label>

                    <select
                        id="classSelect"
                        class="form-select bg-dark text-light border-secondary"
                    >

                        <?php foreach ($classes as $class): ?>

                            <option
                                value="<?= esc($class['id']) ?>"
                                <?= (int)$class['id'] === (int)$classId ? 'selected' : '' ?>
                            >
                                <?= esc($class['class_name']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

        </div>
    </div>


    <!-- Term Tabs -->
    <div class="card bg-dark border-secondary shadow-sm">

        <div class="card-header border-secondary bg-transparent">

            <ul class="nav nav-tabs card-header-tabs" id="termTabs">

                <?php foreach ($terms as $term): ?>

                    <li class="nav-item">

                        <a
                            class="nav-link text-white-50 <?= (int)$term['id'] === (int)$termId ? 'active text-dark' : '' ?>"
                            href="#"
                            data-term-id="<?= esc($term['id']) ?>"
                        >
                            <?= esc($term['name']) ?>

                            <?php if ((int)$term['is_locked'] === 1): ?>
                                <span class="ms-1">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                            <?php endif; ?>

                        </a>

                    </li>

                <?php endforeach; ?>

            </ul>

        </div>


        <!-- Subjects -->
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>
                    <h5 class="mb-1 text-light">
                        Subjects
                    </h5>

                    <?php if ($selectedTerm): ?>

                        <div class="small text-white-50">
                            <?= esc($selectedTerm['name']) ?>

                            <?php if ($semesterId): ?>
                                · Semester <?= esc($semesterId) ?>
                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

                <div class="small text-white-50">
                    <?= count($subjects) ?> subject(s)
                </div>

            </div>


            <?php if (empty($subjects)): ?>

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-journal-x fs-1 text-white-50"></i>
                    </div>

                    <h6 class="text-light">
                        No subjects found
                    </h6>

                    <div class="small text-white-50">
                        There are no subjects assigned to this class division.
                    </div>

                </div>

            <?php else: ?>


                <div class="table-responsive">

                    <table class="table table-dark table-hover align-middle mb-0">

                        <thead>

                            <tr class="text-white-50 small">

                                <th style="width: 130px;">
                                    Code
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th style="width: 160px;">
                                    Status
                                </th>

                                <th style="width: 140px;" class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($subjects as $subject): ?>

                                <?php
                                $gradebook = $gradebooks[$subject['id']] ?? null;
                                ?>

                                <tr>

                                    <!-- Subject Code -->
                                    <td>

                                        <span class="fw-semibold">
                                            <?= esc($subject['subject_code']) ?>
                                        </span>

                                    </td>


                                    <!-- Subject Name -->
                                    <td>

                                        <div class="fw-semibold text-light">
                                            <?= esc($subject['subject_name']) ?>
                                        </div>

                                        <?php if (!empty($subject['description'])): ?>

                                            <div class="small text-white-50">
                                                <?= esc($subject['description']) ?>
                                            </div>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Status -->
                                    <td>

                                        <?php if (!$gradebook): ?>

                                            <span class="badge bg-secondary">
                                                Not Created
                                            </span>

                                        <?php elseif ((int)$gradebook['is_locked'] === 1): ?>

                                            <span class="badge bg-danger">
                                                <i class="bi bi-lock-fill me-1"></i>
                                                Locked
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Active
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Action -->
                                    <td class="text-end">

                                        <?php if (!$gradebook): ?>

                                            <form
                                                action="<?= base_url('gradebook/create') ?>"
                                                method="post"
                                                class="d-inline"
                                            >

                                                <?= csrf_field() ?>

                                                <input
                                                    type="hidden"
                                                    name="academic_year_id"
                                                    value="<?= esc($academicYearId) ?>"
                                                >

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
                                                    value="<?= esc($subject['id']) ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-primary"
                                                >
                                                    <i class="bi bi-plus-lg me-1"></i>
                                                    Create
                                                </button>

                                            </form>


                                        <?php elseif ((int)$gradebook['is_locked'] === 1): ?>

                                            <a
                                                href="<?= base_url('gradebook/open/' . $gradebook['id']) ?>"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                <i class="bi bi-eye me-1"></i>
                                                View
                                            </a>


                                        <?php else: ?>

                                            <a
                                                href="<?= base_url('gradebook/open/' . $gradebook['id']) ?>"
                                                class="btn btn-sm btn-outline-primary"
                                            >
                                                <i class="bi bi-pencil me-1"></i>
                                                Open
                                            </a>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const academicYearSelect = document.getElementById('academicYearSelect');
    const classSelect        = document.getElementById('classSelect');
    const termTabs            = document.querySelectorAll('#termTabs .nav-link');


    function reloadPage(termId = null) {

        const academicYearId = academicYearSelect.value;
        const classId        = classSelect.value;

        if (!termId) {
            const activeTerm = document.querySelector(
                '#termTabs .nav-link.active'
            );

            if (activeTerm) {
                termId = activeTerm.dataset.termId;
            }
        }

        const params = new URLSearchParams();

        params.set('academic_year_id', academicYearId);
        params.set('class_id', classId);
        params.set('term_id', termId);

        window.location.href =
            '<?= base_url('gradebook') ?>?' + params.toString();
    }


    /*
     * Academic Year
     */
    academicYearSelect.addEventListener('change', function () {

        reloadPage();

    });


    /*
     * Class
     */
    classSelect.addEventListener('change', function () {

        reloadPage();

    });


    /*
     * Term
     */
    termTabs.forEach(function (tab) {

        tab.addEventListener('click', function (e) {

            e.preventDefault();

            const termId = this.dataset.termId;

            reloadPage(termId);

        });

    });

});

</script>

<?= $this->endSection() ?>