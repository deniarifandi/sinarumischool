<?php

$scoreLabels = [
    1 => 'Mulai Berkembang',
    2 => 'Berkembang',
    3 => 'Berkembang Sesuai Harapan',
    4 => 'Sangat Berkembang',
];

$dplOptions = [
    1   => 'Beriman & Bertakwa',
    2   => 'Mandiri',
    4   => 'Bernalar Kritis',
    8   => 'Kreatif',
    16  => 'Gotong Royong',
    32  => 'Berkebinekaan Global',
    64  => 'Komunikatif',
    128 => 'Berakhlak Mulia'
];

$intiOptions = [
    1 => 'Mindful (Fokus)',
    2 => 'Meaningful (Bermakna)',
    4 => 'Joyful (Menyenangkan)'
];

$selectedDpl  = (int)($lessonplan['dpl'] ?? 0);
$selectedInti = (int)($lessonplan['inti'] ?? 0);

$totalStudents = count($students);

$scoreCount = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
];

foreach ($students as $student) {
    $assessment = $assessmentMap[$student['id']] ?? [];
    $score = (int)($assessment['score'] ?? 0);

    if (isset($scoreCount[$score])) {
        $scoreCount[$score]++;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<title>
    Penilaian -
    <?= esc($lessonplan['unit_name'] ?? 'Lesson Plan') ?>
</title>

<style>

* {
    box-sizing: border-box;
}

body {
    font-family: 'Times New Roman', Times, serif;
    font-size: 11pt;
    line-height: 1.4;
    color: #000;
    margin: 0;
    padding: 0;
    background: #f0f2f5;
}

.document-container {
    position: relative;
    max-width: 850px;
    margin: 30px auto;
    background: #fff;
    padding: 45px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* PRINT BUTTON */

.btn-print {
    padding: 11px 22px;
    background: #198754;
    color: #fff;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
    font-size: 10pt;
}

.btn-print:hover {
    background: #157347;
}

/* LETTERHEAD */

.letterhead {
    display: flex;
    align-items: center;
    gap: 18px;
    border-bottom: 4px double #000;
    padding-bottom: 12px;
    margin-bottom: 20px;
}

.letterhead-logo {
    width: 70px;
    height: 70px;
    object-fit: contain;
}

.letterhead-text {
    flex: 1;
    text-align: center;
}

.letterhead-text h1 {
    margin: 0;
    font-size: 15pt;
    font-weight: bold;
    text-transform: uppercase;
}

.letterhead-text p {
    margin: 2px 0;
    font-size: 9pt;
}

/* HEADER */

.document-header {
    text-align: center;
    margin-bottom: 20px;
}

.document-header h2 {
    margin: 0;
    font-size: 16pt;
    text-transform: uppercase;
}

/* META */

.meta-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.meta-table td {
    padding: 4px;
    vertical-align: top;
}

.meta-label {
    font-weight: bold;
    width: 110px;
}

.meta-colon {
    width: 15px;
    text-align: center;
}

/* SECTION */

.section-title {
    font-size: 11pt;
    font-weight: bold;
    text-transform: uppercase;
    background: #e9ecef;
    border: 1px solid #000;
    padding: 6px 10px;
    margin: 18px 0 8px;
    page-break-after: avoid;
}

/* INFO TABLE */

.info-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.info-table th,
.info-table td {
    border: 1px solid #000;
    padding: 7px 9px;
    vertical-align: top;
}

.info-table th {
    width: 28%;
    background: #f8f9fa;
    text-align: left;
}

/* TARGET */

.target-container {
    border: 1px solid #000;
    padding: 10px;
}

.target-title {
    font-weight: bold;
    margin-bottom: 5px;
}

.target-list {
    margin: 0 0 10px 0;
    padding-left: 20px;
}

.target-list li {
    margin-bottom: 3px;
}

/* SUMMARY */

.summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.summary-table th,
.summary-table td {
    border: 1px solid #000;
    padding: 7px;
    text-align: center;
}

.summary-table th {
    background: #f8f9fa;
}

.summary-table th:first-child {
    text-align: left;
}

.summary-table td:first-child {
    text-align: left;
}

/* ASSESSMENT TABLE */

.assessment-table {
    width: 100%;
    border-collapse: collapse;
    page-break-inside: auto;
}

.assessment-table tr {
    page-break-inside: avoid;
}

.assessment-table th,
.assessment-table td {
    border: 1px solid #000;
    padding: 7px 6px;
    vertical-align: middle;
}

.assessment-table th {
    background: #e9ecef;
    text-align: center;
    font-weight: bold;
}

.assessment-table .student-number {
    width: 35px;
    text-align: center;
}

.assessment-table .student-name {
    width: 180px;
}

.assessment-table .score-column {
    width: 70px;
    text-align: center;
    font-size: 9pt;
}

.assessment-table .notes-column {
    width: 190px;
}

/* SCORE */

.score-value {
    font-weight: bold;
    text-align: center;
}

.score-1 {
    background: #f8d7da;
}

.score-2 {
    background: #fff3cd;
}

.score-3 {
    background: #d1e7dd;
}

.score-4 {
    background: #cfe2ff;
}

/* SIGNATURE */

.signature-area {
    margin-top: 45px;
    display: table;
    width: 100%;
    page-break-inside: avoid;
}

.signature-box {
    display: table-cell;
    width: 50%;
    text-align: center;
    vertical-align: bottom;
}

/* WATERMARK */

.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 600px;
    height: 600px;
    transform: translate(-50%, -50%);
    background-image: url('<?= base_url('logobrightelly.png') ?>');
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    opacity: 0.12;
    z-index: 0;
    pointer-events: none;
}

.document-container > * {
    position: relative;
    z-index: 1;
}

.document-container > .watermark {
    position: fixed;
    z-index: 0;
}

/* PRINT */

@media print {

    body {
        background: none;
    }

    .document-container {
        box-shadow: none;
        margin: 0;
        padding: 0;
        width: 100%;
        max-width: 100%;
    }

    .no-print {
        display: none !important;
    }

    .watermark {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .section-title,
    .summary-table th,
    .assessment-table th,
    .score-1,
    .score-2,
    .score-3,
    .score-4 {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    @page {
        size: A4 portrait;
        margin: 1.5cm;
    }
}

</style>

</head>

<body>

<div class="document-container">

    <!-- WATERMARK -->
    <div class="watermark"></div>


    <!-- PRINT BUTTON -->

    <div class="no-print"
         style="text-align:right; border-bottom:1px solid #ddd; margin-bottom:25px; padding-bottom:15px;">

        <button class="btn-print"
                onclick="window.print()">

            🖨️ CETAK DOKUMEN

        </button>

        <div style="margin-top:5px; font-size:9pt; color:#666;">
            Gunakan kertas A4 dan aktifkan Print Background Graphics.
        </div>

    </div>


    <!-- LETTERHEAD -->

    <div class="letterhead">

        <img
            class="letterhead-logo"
            src="<?= base_url('logobrightelly.png') ?>"
            alt="Logo"
        >

        <div class="letterhead-text">
            <h1><?= esc($lessonplan['school_name'] ?? 'BrightElly') ?></h1>
            <p><?= esc($lessonplan['school_address'] ?? ' Jl. Danau Toba No. E4/A14, Madyopuro, Kedungkandang, Malang
') ?></p>
            <p><?= esc($lessonplan['school_contact'] ?? 'Telp: 0858-8879-9991 | Email: helpdesk@brightelly.sch.id') ?></p>
        </div>

        <img
            class="letterhead-logo"
            src="<?= base_url('logobrightelly.png') ?>"
            style="visibility:hidden;"
            alt=""
        >

    </div>


    <!-- TITLE -->

    <div class="document-header">

        <h2>
            LEMBAR PENILAIAN PEMBELAJARAN
        </h2>

    </div>


    <!-- LESSON PLAN INFORMATION -->

    <table class="meta-table">

        <tr>

            <td class="meta-label">
                Kelas
            </td>

            <td class="meta-colon">
                :
            </td>

            <td>
                <?= esc($lessonplan['class_name'] ?? '-') ?>
            </td>

            <td class="meta-label">
                Guru
            </td>

            <td class="meta-colon">
                :
            </td>

            <td>
                <?= esc($lessonplan['teacher_name'] ?? '-') ?>
            </td>

        </tr>

        <tr>

            <td class="meta-label">
                Mata Pelajaran
            </td>

            <td class="meta-colon">
                :
            </td>

            <td>
                <?= esc($lessonplan['subject_name'] ?? '-') ?>
            </td>

            <td class="meta-label">
                Semester
            </td>

            <td class="meta-colon">
                :
            </td>

            <td>
                <?= esc($lessonplan['semester'] ?? '-') ?>
            </td>

        </tr>

        <tr>

            <td class="meta-label">
                Topik
            </td>

            <td class="meta-colon">
                :
            </td>

            <td>
                <?= esc($lessonplan['unit_name'] ?? '-') ?>
            </td>

            <td class="meta-label">
                Bulan
            </td>

            <td class="meta-colon">
                :
            </td>

            <td>
                <?= esc($lessonplan['bulan'] ?? '-') ?>
            </td>

        </tr>

        <tr>

            <td class="meta-label">
                Sub Topik
            </td>

            <td class="meta-colon">
                :
            </td>

            <td colspan="4">
                <?= esc($lessonplan['subunit_name'] ?? '-') ?>
            </td>

        </tr>

    </table>


    <!-- OBJECTIVES -->

    <div class="section-title">
        A. Tujuan & Strategi Pembelajaran
    </div>

    <table class="info-table">

        <tr>

            <th>
                Nilai Agama & Moral
            </th>

            <td>

                1.
                <?= esc($lessonplan['agama1_name'] ?? $lessonplan['agama1'] ?? '-') ?>

                <br>

                2.
                <?= esc($lessonplan['agama2_name'] ?? $lessonplan['agama2'] ?? '-') ?>

            </td>

        </tr>

        <tr>

            <th>
                Pengembangan Jati Diri
            </th>

            <td>

                1.
                <?= esc($lessonplan['jati1_name'] ?? $lessonplan['jati1'] ?? '-') ?>

                <br>

                2.
                <?= esc($lessonplan['jati2_name'] ?? $lessonplan['jati2'] ?? '-') ?>

            </td>

        </tr>

        <tr>

            <th>
                Dasar Literasi & STEAM
            </th>

            <td>

                1.
                <?= esc($lessonplan['dasar1_name'] ?? $lessonplan['dasar1'] ?? '-') ?>

                <br>

                2.
                <?= esc($lessonplan['dasar2_name'] ?? $lessonplan['dasar2'] ?? '-') ?>

            </td>

        </tr>

        <tr>

            <th>
                Strategi Mengajar
            </th>

            <td>
                <?= nl2br(esc($lessonplan['pedagogis'] ?? '-')) ?>
            </td>

        </tr>

        <tr>

            <th>
                Kemitraan
            </th>

            <td>
                <?= nl2br(esc($lessonplan['kemitraan'] ?? '-')) ?>
            </td>

        </tr>

    </table>


    <!-- PROFILE -->

    <div class="section-title">
        B. Target Profil & Karakter
    </div>

    <div class="target-container">

        <div class="target-title">
            Profil Pelajar / DPL
        </div>

        <ul class="target-list">

            <?php foreach ($dplOptions as $val => $label): ?>

                <?php if ($selectedDpl & $val): ?>

                    <li>
                        <?= esc($label) ?>
                    </li>

                <?php endif; ?>

            <?php endforeach; ?>

        </ul>


        <div class="target-title">
            Karakter Pembelajaran
        </div>

        <ul class="target-list">

            <?php foreach ($intiOptions as $val => $label): ?>

                <?php if ($selectedInti & $val): ?>

                    <li>
                        <?= esc($label) ?>
                    </li>

                <?php endif; ?>

            <?php endforeach; ?>

        </ul>

    </div>


    <!-- ASSESSMENT SUMMARY -->

    <div class="section-title">
        C. Ringkasan Hasil Penilaian
    </div>

    <table class="summary-table">

        <thead>

            <tr>

                <th>
                    Kategori
                </th>

                <th>
                    Jumlah Siswa
                </th>

                <th>
                    Persentase
                </th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($scoreLabels as $score => $label): ?>

                <?php
                    $percentage = $totalStudents > 0
                        ? round(($scoreCount[$score] / $totalStudents) * 100, 1)
                        : 0;
                ?>

                <tr>

                    <td>
                        <?= $score ?>. <?= esc($label) ?>
                    </td>

                    <td>
                        <?= $scoreCount[$score] ?>
                    </td>

                    <td>
                        <?= $percentage ?>%
                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>


    <!-- STUDENT ASSESSMENT -->

    <div class="section-title">
        D. Penilaian Perkembangan Siswa
    </div>

    <table class="assessment-table">

        <thead>

            <tr>

                <th rowspan="2">
                    No
                </th>

                <th rowspan="2">
                    Nama Siswa
                </th>

                <th colspan="4">
                    Tingkat Perkembangan
                </th>

                <th rowspan="2">
                    Catatan
                </th>

            </tr>

            <tr>

                <th class="score-column">
                    MB
                </th>

                <th class="score-column">
                    B
                </th>

                <th class="score-column">
                    BSH
                </th>

                <th class="score-column">
                    SB
                </th>

            </tr>

        </thead>

        <tbody>

        <?php if (empty($students)): ?>

            <tr>

                <td colspan="7"
                    style="text-align:center;">

                    Tidak ada data siswa.

                </td>

            </tr>

        <?php else: ?>

            <?php foreach ($students as $index => $student): ?>

                <?php

                $studentId = $student['id'];

                $assessment =
                    $assessmentMap[$studentId] ?? [];

                $score =
                    (int)($assessment['score'] ?? 0);

                $notes =
                    $assessment['notes'] ?? '';

                ?>

                <tr>

                    <td class="student-number">
                        <?= $index + 1 ?>
                    </td>

                    <td class="student-name">

                        <?= esc($student['name']) ?>

                    </td>


                    <!-- MB -->

                    <td class="score-column
                        <?= $score === 1 ? 'score-1' : '' ?>">

                        <?= $score === 1 ? '✓' : '' ?>

                    </td>


                    <!-- B -->

                    <td class="score-column
                        <?= $score === 2 ? 'score-2' : '' ?>">

                        <?= $score === 2 ? '✓' : '' ?>

                    </td>


                    <!-- BSH -->

                    <td class="score-column
                        <?= $score === 3 ? 'score-3' : '' ?>">

                        <?= $score === 3 ? '✓' : '' ?>

                    </td>


                    <!-- SB -->

                    <td class="score-column
                        <?= $score === 4 ? 'score-4' : '' ?>">

                        <?= $score === 4 ? '✓' : '' ?>

                    </td>


                    <!-- NOTES -->

                    <td class="notes-column">

                        <?= nl2br(esc($notes ?: '-')) ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

        </tbody>

    </table>


    <!-- SIGNATURE -->

    <div class="signature-area">

        <div class="signature-box">

            Mengetahui,<br>

            Kepala Sekolah

            <br><br><br><br><br>

            <strong>
                ( ___________________________ )
            </strong>

        </div>


        <div class="signature-box">

            Malang,
            <?= date('d F Y') ?>

            <br>

            Guru

            <br><br><br><br><br>

            <strong>
                ( <?= esc($lessonplan['teacher_name'] ?? '___________________________') ?> )
            </strong>

        </div>

    </div>

</div>

</body>
</html>