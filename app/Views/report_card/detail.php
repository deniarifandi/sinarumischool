<?php
$religion = strtoupper(trim($student['murid_agama'] ?? ''));

$religionMap = [
    'ISLAM'     => 'Islam',
    'CHRISTIAN' => 'Christian',
    'KRISTEN'   => 'Christian',

    'KATOLIK'   => 'Catholic',
    'KATOLIK '  => 'Catholic',
    'CATHOLIC'  => 'Catholic',

    'BUDHA'     => 'Buddhist',
    'BUDDHA'    => 'Buddhist',

    'HINDU'     => 'Hindu',
];

$religionLabel = $religionMap[$religion] ?? '-';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Progress Report - <?= esc($student['name'] ?? '') ?></title>

    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            width: 90%;
            margin: 0 auto;
            color: #000;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
        }

        .main-table,
        .main-table th,
        .main-table td {
            border: 1px solid #000;
        }

        .main-table th,
        .main-table td {
            padding: 5px;
        }

        .borderless,
        .borderless td,
        .borderless th {
            border: 0 !important;
        }

        h2, h3 {
            margin: 0;
            padding: 0;
        }

        .center {
            text-align: center;
        }

        .low-score {
            color: red;
        }

        .print-button {
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 10px 18px;
            background: #222;
            color: white;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>

<body>

<button class="print-button no-print" onclick="window.print()">
    Print Report
</button>

<?php
// =========================================================
// BASIC DATA
// =========================================================

$studentName = $student['name'] ?? '-';
$className   = $class['class_name'] ?? '-';

$academicYearName = $academicYear['name'] ?? '-';
$termName         = $term['name'] ?? '-';
$semesterName     = $semester['name'] ?? '';

$kkm = 75;


// =========================================================
// SEMESTER LABEL
// =========================================================

$semesterNumber = $semester['number'] ?? '';

if ($semesterNumber == 1) {
    $semesterLabel = 'Semester 1';
} elseif ($semesterNumber == 2) {
    $semesterLabel = 'Semester 2';
} else {
    $semesterLabel = $semesterName;
}
?>

<!-- ========================================================= -->
<!-- HEADER -->
<!-- ========================================================= -->

<div style="margin-top:20px;">
    <img
        src="<?= base_url('header_mli_report.png') ?>"
        style="max-width:100%;"
        alt="School Logo"
    >
</div>

<br>

<table class="borderless" style="width:100%;">
    <tr>
        <td style="width:20%;">Student's Name</td>
        <td style="width:50%;">: <?= esc($studentName) ?></td>
        <td style="width:30%;">
            <?= esc($semesterLabel) ?> AY <?= esc($academicYearName) ?>
        </td>
    </tr>

    <tr>
        <td>Class</td>
        <td>: <?= esc($className) ?></td>
        <td>
            Term : <?= esc($termName) ?>
        </td>
    </tr>
     <tr>
        <td>Religion</td>
        <td>: <?= esc($religionLabel) ?></td>
    </tr>
</table>

<br><br>


<!-- ========================================================= -->
<!-- GRADE TABLE -->
<!-- ========================================================= -->

<table class="main-table" style="width:100%;">

    <!-- TITLE -->
    <tr>
        <td colspan="8" class="center">
            <h2>
                <?= strtoupper(esc($termName)) ?> - PROGRESS REPORT
            </h2>
        </td>
    </tr>


    <!-- SUBJECT + TERM -->
    <tr>
        <td colspan="2" rowspan="3" class="center">
            <h2>Subject</h2>
        </td>

        <td colspan="6" class="center">
            <?= strtoupper(esc($termName)) ?>
        </td>
    </tr>


    <!-- CATEGORY -->
    <tr>
        <td colspan="4" class="center">
            Chapter Test
        </td>

        <td colspan="2" class="center">
            Project
        </td>
    </tr>


    <!-- COLUMN -->
    <tr>
        <td class="center">1</td>
        <td class="center">Remedial</td>

        <td class="center">2</td>
        <td class="center">Remedial</td>

        <td class="center">Individual Project</td>
        <td class="center">Group Project</td>
    </tr>


<?php
// =========================================================
// SUBJECT ROWS
// =========================================================


$counter = 0;

$studentReligion = strtolower(trim($student['murid_agama'] ?? ''));

// Normalize database religion values
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

$studentReligion = $religionMap[$studentReligion] ?? null;


foreach ($subjects as $subject):

    $subjectName = trim($subject['subject_name'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | Religion Subject Filter
    |--------------------------------------------------------------------------
    |
    | Example:
    | Religion : Islam
    | Religion : Christian
    | Religion : Catholic
    | Religion : Buddhist
    | Religion : Hindu
    |
    | Only show the religion matching the student.
    |
    */

    if (preg_match('/^Religion\s*:\s*(.+)$/i', $subjectName, $matches)) {

        $subjectReligion = strtolower(trim($matches[1]));

        // Normalize subject religion
        $subjectReligion = $religionMap[$subjectReligion] ?? null;

        // Skip if student's religion doesn't match
        if (!$studentReligion || $subjectReligion !== $studentReligion) {
            continue;
        }
    }


    $counter++;

    $subjectId = $subject['id'];

    $score = $scores[$subjectId] ?? [
        'ct1'                => '-',
        'ct1_remedial'       => '-',
        'ct2'                => '-',
        'ct2_remedial'       => '-',
        'individual_project' => '-',
        'group_project'      => '-',
    ];


    $values = [
        $score['ct1'] ?? '-',
        $score['ct1_remedial'] ?? '-',
        $score['ct2'] ?? '-',
        $score['ct2_remedial'] ?? '-',
        $score['individual_project'] ?? '-',
        $score['group_project'] ?? '-',
    ];
?>

<tr>

    <td width="5%" class="center">
        <?= $counter ?>
    </td>

    <td width="35%">
        <?= esc($subjectName) ?>
    </td>

    <?php foreach ($values as $value): ?>

        <?php
        if ($value === null || $value === '') {
            $value = '-';
        }

        $isLow = (
            is_numeric($value)
            && (float) $value < $kkm
        );
        ?>

        <td
            width="10%"
            class="center <?= $isLow ? 'low-score' : '' ?>"
        >
            <?= esc($value) ?>
        </td>

    <?php endforeach; ?>

</tr>

<?php endforeach; ?>


<?php if ($counter === 0): ?>

    <tr>
        <td colspan="8" class="center">
            No subjects available.
        </td>
    </tr>

<?php endif; ?>

</table>

<br>


<!-- ========================================================= -->
<!-- ATTENDANCE -->
<!-- ========================================================= -->

<?php

$sickness      = $attendance['sickness'] ?? 0;
$authorized    = $attendance['authorized'] ?? 0;
$unauthorized  = $attendance['unauthorized'] ?? 0;
$totalMeetings = $attendance['total_meetings'] ?? 0;


if ($totalMeetings > 0) {

    $sickPct = round(
        ($sickness / $totalMeetings) * 100
    );

    $authorizedPct = round(
        ($authorized / $totalMeetings) * 100
    );

    $unauthorizedPct = round(
        ($unauthorized / $totalMeetings) * 100
    );

} else {

    $sickPct         = 0;
    $authorizedPct   = 0;
    $unauthorizedPct = 0;
}

?>

<table style="width:100%;" class="borderless">

    <tr>

        <td colspan="3">
            <h3>Attendance</h3>
        </td>

        <td></td>

        <td colspan="2" class="center">
            Teacher
        </td>

        <td colspan="2" class="center">
            Parent
        </td>

    </tr>


    <tr>

        <td colspan="2">
            Sickness Absence
        </td>

        <td class="center">
            <?= $sickPct ?> %
        </td>

        <td></td>

        <td
            colspan="2"
            rowspan="3"
            style="
                text-align:center;
                vertical-align:bottom;
                font-size:10px;
            "
        >
            <?= esc($teacher['name'] ?? '') ?>
        </td>

        <td colspan="2" rowspan="3"></td>

    </tr>


    <tr>

        <td colspan="2">
            Authorized Absence
        </td>

        <td class="center">
            <?= $authorizedPct ?> %
        </td>

        <td></td>

    </tr>


    <tr>

        <td colspan="2">
            Unauthorized Absence
        </td>

        <td class="center">
            <?= $unauthorizedPct ?> %
        </td>

        <td></td>

    </tr>

</table>

<br><br><br><br>


<!-- ========================================================= -->
<!-- SIGNATURE -->
<!-- ========================================================= -->

<table
    style="width:100%; margin-top:10px;"
    class="borderless"
>

    <tr>
        <td class="center">
            Malang, <?= date('j F Y') ?>
        </td>
    </tr>

    <tr>
        <td class="center">
            Principal
            <br><br><br><br>
        </td>
    </tr>

    <tr>
        <td class="center">
            Rurik Herawati, M.Pd.
        </td>
    </tr>

</table>

</body>
</html>