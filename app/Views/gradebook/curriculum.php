<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-0 py-3">

    <!-- HEADER -->
    <div class="glass-card p-4 mb-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold text-light mb-2">
                    <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>
                    Curriculum Gradebook
                </h4>

                <div class="d-flex flex-wrap gap-2">

                    <span class="badge bg-primary">
                        <i class="bi bi-people-fill me-1"></i>
                        <?= esc($class['class_name']) ?>
                    </span>

                    <span class="badge bg-secondary">
                        <i class="bi bi-mortarboard-fill me-1"></i>
                        <?= esc($class['grade_name']) ?>
                    </span>

                    <span class="badge bg-info">
                        <?= esc($term['name']) ?>
                    </span>

                    <span class="badge bg-dark border">
                        <?= esc($academicYear['name']) ?>
                    </span>

                </div>
            </div>

            <div>
                <button
                    type="button"
                    onclick="window.close();"
                    class="btn btn-outline-light rounded-pill px-4">
                    <i class="bi bi-x-lg me-1"></i>
                    Close
                </button>
            </div>

        </div>

    </div>


    <!-- SUBJECT CARDS -->

    <?php if (empty($subjects)): ?>

        <div class="alert alert-warning">
            No subjects found for this division.
        </div>

    <?php else: ?>

        <?php foreach ($subjects as $subjectData): ?>

            <?php
            // =====================================================
            // SUBJECT DATA
            // =====================================================

            $subject = $subjectData['subject'];
            $scores  = $subjectData['scores'];

            $subjectName = trim($subject['subject_name'] ?? '');


            // =====================================================
            // RELIGION MAPPING
            // =====================================================

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


            // =====================================================
            // DETECT RELIGION SUBJECT
            // =====================================================

            $isReligionSubject = false;
            $subjectReligion   = null;

            if (
                preg_match(
                    '/^Religion\s*:\s*(.+)$/i',
                    $subjectName,
                    $matches
                )
            ) {

                $isReligionSubject = true;

                $subjectReligion = strtolower(
                    trim($matches[1])
                );

                $subjectReligion =
                    $religionMap[$subjectReligion] ?? null;
            }
            ?>

            <div class="glass-card p-3 mb-4">

                <!-- SUBJECT HEADER -->

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="fw-bold text-light mb-1">

                            <i class="bi bi-book-half text-warning me-2"></i>

                            <?= esc($subjectName) ?>

                        </h5>

                        <?php if (!empty($subject['subject_code'])): ?>

                            <small class="text-white-50">
                                <?= esc($subject['subject_code']) ?>
                            </small>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- TABLE -->

                <div class="table-responsive">

                    <table class="table table-sm table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th rowspan="2" class="text-center">
                                    No
                                </th>

                                <th rowspan="2" style="min-width:200px;">
                                    Student
                                </th>

                                <th colspan="2" class="text-center">
                                    Chapter Test 1
                                </th>

                                <th colspan="2" class="text-center">
                                    Chapter Test 2
                                </th>

                                <th rowspan="2" class="text-center">
                                    Individual<br>
                                    Project
                                </th>

                                <th rowspan="2" class="text-center">
                                    Group<br>
                                    Project
                                </th>

                            </tr>


                            <tr>

                                <th class="text-center">
                                    CT1
                                </th>

                                <th class="text-center">
                                    Rem.
                                </th>

                                <th class="text-center">
                                    CT2
                                </th>

                                <th class="text-center">
                                    Rem.
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if (empty($students)): ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center text-muted"
                                >
                                    No students found.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($students as $index => $student): ?>

                                <?php
                                // =================================================
                                // STUDENT
                                // =================================================

                                $studentId = $student['id'];

                                $score = $scores[$studentId] ?? [];


                                // =================================================
                                // STUDENT RELIGION
                                // =================================================

                                $studentReligion = strtolower(
                                    trim(
                                        $student['murid_agama'] ?? ''
                                    )
                                );

                                $studentReligion =
                                    $religionMap[$studentReligion] ?? null;


                                // =================================================
                                // RELIGION MISMATCH
                                // =================================================

                                $religionMismatch = (
                                    $isReligionSubject &&
                                    $subjectReligion !== null &&
                                    $studentReligion !== $subjectReligion
                                );


                                // Grey row
                                $rowClass = $religionMismatch
                                    ? 'table-secondary text-muted'
                                    : '';
                                ?>

                                <tr class="<?= $rowClass ?>">

                                    <!-- NUMBER -->

                                    <td class="text-center">

                                        <?= $index + 1 ?>

                                    </td>


                                    <!-- STUDENT -->

                                    <td>

                                        <strong>
                                            <?= esc($student['name']) ?>
                                        </strong>

                                    </td>


                                    <!-- CT1 -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct1'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- CT1 REMEDIAL -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct1_remedial'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- CT2 -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct2'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- CT2 REMEDIAL -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct2_remedial'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- INDIVIDUAL PROJECT -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['individual_project'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- GROUP PROJECT -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['group_project'] ?? '-'
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>