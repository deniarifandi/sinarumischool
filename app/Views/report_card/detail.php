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
        <td style="">
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

<table style="width:100%; border-collapse:collapse;">

    <!-- TITLE -->
    <tr style="border:1px solid #000; ">
        <td colspan="8" class="center" style="padding: 5px">
            <h2>
                <?= strtoupper(esc($termName)) ?> - PROGRESS REPORT
            </h2>
        </td>
    </tr>


    <!-- SUBJECT + TERM -->
    <tr style="border:1px solid #000;">
        <td colspan="2" rowspan="3" class="center" style="border:1px solid #000;">
            <h2>Subject</h2>
        </td>

        <td colspan="6" class="center" style="padding: 5px;">
            <?= strtoupper(esc($termName)) ?>
        </td>
    </tr>


    <!-- CATEGORY -->
    <tr style="border:1px solid #000;">
        <td colspan="4" class="center" style="border:1px solid #000; padding:3px">
            Chapter Test
        </td>

        <td colspan="2" class="center" style="border:1px solid #000;">
            Project
        </td>
    </tr>


    <!-- COLUMN -->
    <tr style="border:1px solid #000;">
        <td class="center" style="border:1px solid #000;">1</td>
        <td class="center" style="border:1px solid #000; padding: 3px">Remedial</td>

        <td class="center" style="border:1px solid #000;">2</td>
        <td class="center" style="border:1px solid #000;">Remedial</td>

        <td class="center" style="border:1px solid #000;">Individual Project</td>
        <td class="center" style="border:1px solid #000;">Group Project</td>
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

    // =====================================================
    // RELIGION SUBJECT FILTER
    // =====================================================

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

    <!-- SUBJECT ROW -->
    <tr>

        <td width="5%" class="center" style="border:1px solid #000;">
            <?= $counter ?>
        </td>

        <td width="35%" style="border:1px solid #000; padding: 3px">
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
                style="border:1px solid #000;"
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


    <!-- =====================================================
         SPACER
         ===================================================== -->

    <tr style="height:20px;">
        <td colspan="8" style="border:none !important;"></td>
    </tr>


    <!-- =====================================================
         ATTENDANCE HEADER
         ===================================================== -->

    <tr>

        <td colspan="3" style="border:1px solid #000;">
            <h3 style="margin:2px;">
                Attendance
            </h3>
        </td>

        <!-- SEPARATOR -->
        <td style="border:none !important;"></td>

        <td colspan="2"
            class="center"
            style="border:1px solid #000;">
            Teacher
        </td>

        <td colspan="2"
            class="center"
            style="border:1px solid #000;">
            Parent
        </td>

    </tr>


    <!-- =====================================================
         SICKNESS ABSENCE
         ===================================================== -->

    <tr>

        <td colspan="2"
            style="border:1px solid #000; padding: 3px">
            Sickness Absence
        </td>

        <td class="center"
            style="border:1px solid #000;">
            <?= $sickPct ?> %
        </td>

        <!-- SEPARATOR -->
        <td style="border:none !important;"></td>

        <td
            colspan="2"
            rowspan="3"
            style="
                border:1px solid #000;
                text-align:center;
                vertical-align:bottom;
                font-size:10px;
            "
        >
            <?= esc($teacher['name'] ?? '') ?>
        </td>

        <td
            colspan="2"
            rowspan="3"
            style="border:1px solid #000;"
        ></td>

    </tr>


    <!-- =====================================================
         AUTHORIZED ABSENCE
         ===================================================== -->

    <tr>

        <td colspan="2"
            style="border:1px solid #000;  padding: 3px">
            Authorized Absence
        </td>

        <td class="center"
            style="border:1px solid #000;">
            <?= $authorizedPct ?> %
        </td>

        <!-- SEPARATOR -->
        <td style="border:none !important;"></td>

    </tr>


    <!-- =====================================================
         UNAUTHORIZED ABSENCE
         ===================================================== -->

    <tr>

        <td colspan="2"
            style="border:1px solid #000;  padding: 3px">
            Unauthorized Absence
        </td>

        <td class="center"
            style="border:1px solid #000;">
            <?= $unauthorizedPct ?> %
        </td>

        <!-- SEPARATOR -->
        <td style="border:none !important;"></td>

    </tr>

</table>

<br><br><br>

<!-- ========================================================= -->
<!-- OBJECTIVE-BASED SCORES (PAGE 2) -->
<!-- ========================================================= -->

<div style="page-break-before: always;">
    <br><br><br>
    <h2 class="center">Holistic Progress Report</h2>
    
    <?php foreach ($subjects as $subject): 
        $subjectId   = $subject['id'];
        $objList     = $allObjectives[$subjectId] ?? [];
        $subjectName = trim($subject['subject_name'] ?? '');
        
        // Filter religion subjects
        if (preg_match('/^Religion\s*:\s*(.+)$/i', $subjectName, $matches)) {
            $sRel = strtolower(trim($matches[1]));
            if ($sRel !== $studentReligion) {
                continue;
            }
        }

        // Skip jika tidak ada objective
        if (empty($objList)) {
            continue;
        }
    ?>
    
    <h3 style="margin-top:15px; display:inline-block;">
        <?= esc($subjectName) ?>
    </h3>

    <table class="main-table" style="width:100%; margin-top:5px; margin-bottom:20px;">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th>Objective</th>
                <th style="width:10%; display: none;">Score</th>
                <th style="width:8%;">A</th>
                <th style="width:8%;">B</th>
                <th style="width:8%;">C</th>
                <th style="width:8%;">D</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($objList as $index => $obj): 
                $score = $objectiveScores[$subjectId][$obj['objective_id']] ?? null;
                $grade = null;

                // Determine grade
                if (is_numeric($score)) {
                    $score = (float) $score;
                    if ($score >= 91) {
                        $grade = 'A';
                    } elseif ($score >= 83) {
                        $grade = 'B';
                    } elseif ($score >= 75) {
                        $grade = 'C';
                    } else {
                        $grade = 'D';
                    }
                }
            ?>
            <tr>
                <td class="center"><?= $index + 1 ?></td>
                <td><?= esc($obj['objective_name'] ?? '-') ?></td>
                <td class="center" style="display:none"><?= $score ?? '-' ?></td>
                <td class="center"><?= $grade === 'A' ? '✓' : '' ?></td>
                <td class="center"><?= $grade === 'B' ? '✓' : '' ?></td>
                <td class="center"><?= $grade === 'C' ? '✓' : '' ?></td>
                <td class="center"><?= $grade === 'D' ? '✓' : '' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="7" style="font-size: 0.85em; padding: 8px; background-color: #f9f9f9; text-align: left;">
                    <strong>Formative Assessment Conversion Grades:</strong> &nbsp;
                    A (91-100) &nbsp;|&nbsp; B (83-90) &nbsp;|&nbsp; C (75-82) &nbsp;|&nbsp; D (<75)
                </td>
            </tr>
        </tfoot>
    </table>
    
    <?php endforeach; ?>
</div>

<br>


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