<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0 py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h5 class="fw-bold text-light mb-1">
                <i class="bi bi-bar-chart-fill text-primary me-2"></i>
                Grade Report
            </h5>

            <div class="text-white-50 small">
                <?= esc($class['grade_name']) ?>
                -
                <?= esc($class['class_name']) ?>
                |
                <?= esc($term['name']) ?>
                |
                <?= esc($academicYear['name']) ?>
            </div>
        </div>

        <a href="<?= base_url('curriculum') ?>"
           class="btn btn-outline-light btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    <div class="glass-card p-3">

        <div class="table-responsive">

            <table class="table table-sm table-bordered align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th rowspan="2"
                            class="text-center"
                            style="width:50px;">
                            No
                        </th>

                        <th rowspan="2"
                            style="min-width:200px;">
                            Student
                        </th>

                        <?php foreach ($subjects as $subject): ?>

                            <th colspan="1"
                                class="text-center"
                                style="min-width:110px;">
                                <?= esc($subject['subject_name']) ?>
                            </th>

                        <?php endforeach; ?>

                    </tr>

                    <tr>

                        <?php foreach ($subjects as $subject): ?>

                            <th class="text-center small">
                                Score
                            </th>

                        <?php endforeach; ?>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($students as $index => $student): ?>

                    <tr>

                        <td class="text-center">
                            <?= $index + 1 ?>
                        </td>

                        <td class="fw-semibold">
                            <?= esc($student['name']) ?>
                        </td>

                        <?php foreach ($subjects as $subject): ?>

                            <?php

                            $gradebook =
                                $gradebooks[$subject['id']]
                                ?? null;

                            $score = null;

                            if ($gradebook) {

                                $score =
                                    $scores[$student['id']]
                                    [$gradebook['id']]
                                    ?? null;
                            }

                            /*
                             * Determine displayed score.
                             *
                             * Here I use the highest applicable
                             * score between original/remedial.
                             */

                            $displayScore = null;

                            if ($score) {

                                $normalScores = [
                                    $score['ct1'] ?? null,
                                    $score['ct2'] ?? null,
                                    $score['individual_project'] ?? null,
                                    $score['group_project'] ?? null,
                                ];

                                $normalScores = array_filter(
                                    $normalScores,
                                    fn($v) => $v !== null && $v !== ''
                                );

                                if (!empty($normalScores)) {
                                    $displayScore = round(
                                        array_sum($normalScores) /
                                        count($normalScores),
                                        2
                                    );
                                }
                            }

                            ?>

                            <td class="text-center">

                                <?php if ($displayScore !== null): ?>

                                    <span class="fw-bold">
                                        <?= esc($displayScore) ?>
                                    </span>

                                <?php else: ?>

                                    <span class="text-muted">
                                        -
                                    </span>

                                <?php endif; ?>

                            </td>

                        <?php endforeach; ?>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= $this->endSection() ?>