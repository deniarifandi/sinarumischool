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
// LIST OF RELIGIONS PRESENT AMONG STUDENTS (used by both tabs' filters)
// ============================================================

$religions = [];

foreach ($students as $student) {
    if (!empty($student['murid_agama'])) {
        $religion = trim($student['murid_agama']);

        if (!in_array($religion, $religions)) {
            $religions[] = $religion;
        }
    }
}

sort($religions);


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

<?= $this->include('gradebook/_styles') ?>

<?php
// ============================================================
// CI4 include() passes data via the renderer's setData/tempData,
// NOT as the second argument (that's reserved for 3rd parties).
// Set everything once, then include the partials.
// ============================================================
$this->setData([
    'subject'            => $subject,
    'class'              => $class,
    'term'               => $term,
    'semester'           => $semester,
    'academicYear'       => $academicYear,
    'isLocked'           => $isLocked,
    'isReligionSubject'  => $isReligionSubject,
    'subjectReligion'    => $subjectReligion,
    'religionMap'        => $religionMap,
    'religions'          => $religions,
    'termId'             => $termId,
    'classId'            => $classId,
    'subjectId'          => $subjectId,
    'gradebookId'        => $gradebookId,
    'students'           => $students,
    'scores'             => $scores,
    'oldInput'           => $oldInput,
    'kkm'                => $kkm,
    'objectives'         => $objectives,
    'objectiveScores'    => $objectiveScores,
    'outcomes'           => $outcomes ?? [],
]);
?>

<div class="glass-card p-3">

    <?= $this->include('gradebook/_header') ?>

    <?= $this->include('gradebook/_alerts') ?>

    <!-- ============================================================
         TABS: CT <-> Objective-Based
         ============================================================ -->

    <?= $this->include('gradebook/_tabs_nav') ?>

    <div class="tab-content bg-light p-3" id="gbTabContent">

        <div class="tab-pane fade show active" id="tab-ct" role="tabpanel" aria-labelledby="tab-ct-tab">

            <?= $this->include('gradebook/_tab_ct') ?>

        </div><!-- /.tab-ct -->

        <!-- ============================================================
             TAB 2: OBJECTIVE-BASED
             ============================================================ -->

        <div class="tab-pane fade" id="tab-objective" role="tabpanel" aria-labelledby="tab-objective-tab">

            <?= $this->include('gradebook/_tab_objective') ?>

        </div><!-- /.tab-objective -->

    </div><!-- /.tab-content -->

</div><!-- /.glass-card -->

<?= $this->endSection() ?>


<?= $this->section('script') ?>

<?= $this->include('gradebook/_scripts_ct') ?>

<?= $this->include('gradebook/_scripts_objective') ?>

<?= $this->include('gradebook/_scripts_flash') ?>

<?= $this->endSection() ?>
