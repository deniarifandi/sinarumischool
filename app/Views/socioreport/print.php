
<?php // CLASS -> CATEGORY -> RESULT -> DESCRIPTION
$finalDescription;
$descriptionLower = [
    'Life' => [
        'A' => 'Advanced<br>
The student consistently keeps trying even when tasks are challenging. Follows instructions independently and maintains focus during learning activities.<br>
<i>Berkembang sangat baik<br>
Siswa secara konsisten terus berusaha meskipun tugas terasa menantang. Mampu mengikuti instruksi secara mandiri dan mempertahankan fokus selama kegiatan pembelajaran.</i>',
        'B' => 'Consistent<br>
The student is able to complete tasks and follow instructions with reasonable effort. Occasionally needs guidance but shows persistence when facing challenges.<br>
<i>Konsisten<br>
Siswa mampu menyelesaikan tugas dan mengikuti instruksi dengan usaha yang memadai. Sesekali masih memerlukan arahan, namun menunjukkan ketekunan ketika menghadapi tantangan.</i>',
        'C' => 
        'Developing<br>
The student is beginning to attempt simple tasks but still gives up easily and often needs reminders to keep trying. Focus during work time is not yet stable, especially when tasks feel difficult.<br>
<i>Berkembang<br>
Siswa mulai mencoba melakukan tugas-tugas sederhana, namun masih mudah menyerah dan sering memerlukan pengingat untuk terus berusaha. Fokus selama waktu pengerjaan tugas belum stabil, terutama ketika tugas terasa sulit.</i>'
    ],
   'Soc' => [
    'C' => 'Developing<br>
The student is beginning to interact with peers but still struggles with sharing and sometimes plays roughly.<br>
<i>Berkembang<br>
Siswa mulai berinteraksi dengan teman sebaya, namun masih mengalami kesulitan dalam berbagi dan terkadang bermain dengan cara yang kurang tepat.</i>',
    'B' => 'Consistent<br>
The student plays and works with peers and is starting to share during group activities.<br>
<i>Konsisten<br>
Siswa bermain dan bekerja sama dengan teman sebaya serta mulai belajar berbagi dalam kegiatan kelompok.</i>',
    'A' => 'Advanced<br>
The student is friendly, willing to share, and shows positive social behavior during play and learning<br>
<i>Berkembang sangat baik<br>
Siswa bersikap ramah, bersedia berbagi, dan menunjukkan perilaku sosial yang positif selama kegiatan bermain dan belajar.</i>',
],
    'Dev' => [
        'C' => 'Developing<br>
The student reacts emotionally during small conflicts and needs support to calm down and rejoin activities.<br>
<i>Berkembang<br>
Siswa masih menunjukkan reaksi emosional saat menghadapi konflik kecil dan memerlukan dukungan untuk menenangkan diri serta kembali bergabung dalam kegiatan.</i>',
        'B' => 'Consistent<br>
The student can accept reminders and is usually able to calm down after minor conflicts with occasional support.<br>
<i>Konsisten<br>
Siswa menerima saat diingatkan dan umumnya mampu menenangkan diri setelah konflik kecil dengan dukungan sesekali.</i>',
        'A' => '
Advanced<br>
The student remains calm when corrected and quickly refocuses after minor conflicts.<br>
<i>Berkembang sangat baik<br>
Siswa tetap tenang ketika diberikan teguran dan dengan cepat dapat kembali fokus setelah konflik kecil.</i>',
    ]
];
$descriptionMid = [
    'Life' => [
        'C' => 'Developing<br>
The student attempts independent work but is often distracted or avoids challenging tasks. Work focus still needs improvement.<br><i>
Siswa mencoba bekerja secara mandiri, namun sering terdistraksi atau menghindari tugas yang menantang. Fokus dalam bekerja masih perlu ditingkatkan.</i>',
        'B' => 'Consistent<br>
The student stays focused during work time and attempts challenging tasks. Sometimes still needs encouragement when difficulties arise.<br><i>
Konsisten<br>
Siswa tetap fokus selama waktu bekerja dan berusaha mengerjakan tugas yang menantang. Terkadang masih memerlukan dorongan ketika menghadapi kesulitan.</i>',
        'A' => 'Advanced<br>
The student works independently, remains focused, and willingly takes on challenging tasks without avoidance.<br><i>
Berkembang sangat baik<br>
Siswa bekerja secara mandiri, tetap fokus, dan dengan sukarela mengambil tugas yang menantang tanpa menghindarinya.</i>',
    ],
    'Soc' => [
        'C' => 'Developing<br>
The student may interrupt others or disrupt group work and needs reminders about cooperative behavior.<br><i>
Berkembang<br>
Siswa terkadang menyela teman atau mengganggu kegiatan kelompok dan masih memerlukan pengingat untuk menunjukkan perilaku kerja sama yang baik.</i>',
        'B' => 'Consistent<br>
The student cooperates and listens to peers, though occasional reminders are still needed<br><i>
Konsisten<br>
Siswa dapat bekerja sama dan mendengarkan teman, meskipun terkadang masih memerlukan pengingat.</i>',
        'A' => 'Advanced<br>
The student works well in groups, respects others’ ideas, and participates positively in discussions.<br><i>
Berkembang sangat baik<br>
Siswa dapat bekerja dengan baik dalam kelompok, menghargai ide orang lain, dan berpartisipasi secara positif dalam diskusi.</i>',
    ],
    'Dev' => [
        'C' => 'Developing<br>
The student sometimes rejects feedback or gives up when facing difficulties and needs guidance to try again.<br><i>
Berkembang<br>
Siswa terkadang menolak umpan balik atau mudah menyerah saat menghadapi kesulitan, sehingga masih memerlukan bimbingan untuk mencoba kembali.</i>',
        'B' => 'Consistent<br>
The student accepts feedback fairly well and attempts to correct mistakes with some encouragement<br><i>
Konsisten<br>
Siswa dapat menerima umpan balik dengan cukup baik dan berusaha memperbaiki kesalahan dengan sedikit dorongan.</i>',
        'A' => 'Advanced<br>
The student accepts feedback positively and quickly tries again after making mistakes<br><i>
Sangat berkembang<br>
Siswa menerima umpan balik dengan sikap positif dan dengan cepat mencoba kembali setelah melakukan kesalahan.</i>',
    ]
];
$descriptionUpper = [
    'Life' => [
        'C' => 'Developing<br>
The student listens to feedback but does not always use it to improve work and may avoid new or difficult tasks<br><i>
Berkembang<br>
Siswa mendengarkan umpan balik, namun belum selalu menggunakannya untuk memperbaiki hasil kerja dan terkadang menghindari tugas yang baru atau sulit.</i>',
        'B' => 'Consistent<br>
The student begins to apply feedback to improve work and tries new strategies, though still hesitant at times.<br><i>
Konsisten<br>
Siswa mulai menerapkan umpan balik untuk memperbaiki hasil kerja dan mencoba strategi baru, meskipun terkadang masih ragu-ragu.</i>',
        'A' => 'Advanced<br>
The student actively uses feedback to improve performance and confidently tries new strategies when facing challenges.<br><i>
Berkembang sangat baik<br>
Siswa secara aktif menggunakan umpan balik untuk meningkatkan kinerja dan dengan percaya diri mencoba strategi baru saat menghadapi tantangan.</i>',
    ],
    'Soc' => [
        'C' => 'Developing<br>
The student may withdraw or dominate group activities, making collaboration less effective.<br><i>
Berkembang<br>
Siswa terkadang menarik diri atau justru mendominasi kegiatan kelompok sehingga kerja sama menjadi kurang efektif.</i>',
        'B' => 'Consistent<br>
The student contributes to group work and begins supporting peers in completing tasks.<br><i>
Konsisten<br>
Siswa berkontribusi dalam kerja kelompok dan mulai mendukung teman dalam menyelesaikan tugas.</i>',
        'A' => 'Advanced<br>
The student actively contributes ideas, supports teammates, and demonstrates mature collaboration skills.<br><i>
Berkembang sangat baik<br>
Siswa secara aktif menyumbangkan ide, mendukung teman satu tim, dan menunjukkan keterampilan kolaborasi yang matang.</i>',
    ],
    'Dev' => [
        'C' => 'Developing<br>
The student may overreact during conflict or needs a long time to continue working after making mistakes.<br><i>
Berkembang<br>
Siswa terkadang bereaksi berlebihan saat terjadi konflik atau membutuhkan waktu yang cukup lama untuk kembali melanjutkan pekerjaan setelah melakukan kesalahan.</i>',
        'B' => 'Consistent<br>
The student is generally able to manage emotions and recover after setbacks<br><i>
Siswa umumnya mampu mengelola emosi dan dapat kembali bangkit setelah mengalami kesulitan atau kegagalan.<br></i>',
        'A' => 'Advanced<br>
The student stays calm during conflict and demonstrates resilience by quickly continuing work after mistakes<br><i>
Berkembang Sangat Baik<br>
Siswa tetap tenang saat menghadapi konflik dan menunjukkan ketangguhan dengan cepat melanjutkan pekerjaan setelah melakukan kesalahan.</i>',
    ]
];

// // usage
// $class   = 'Class2';
// $category = 'Life'; // Life / Soc / Dev
// $result   = 'B';    // A / B / C ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Socio Emotional Report</title>

    <style>
        @page { size:A4; margin:20mm; }
        body{ font-family:Arial,sans-serif; font-size:12px; color:#000; }
        .page{ page-break-after:always; }
        .page:last-child{ page-break-after:auto; }
        h3,h4{ margin:0; }
        .header{ margin-bottom:12px; }
        .student-name{ font-size:16px; font-weight:bold; margin:10px 0; }
        .meta{ font-size:11px; }
        table{ width:100%; border-collapse:collapse; margin-top:8px; }
        th,td{ border:1px solid #000; padding:6px; text-align:left; }
        th{ background:#eee; font-weight:600; }
        .signature{ margin-top:40px; width:40%; text-align:center; float:right; }
        .summary-box{
            border:1px solid #000;
            padding:10px;
            margin-bottom:15px;
        }

        .calculation-table th,
        .calculation-table td{
            padding:5px 6px;
            vertical-align:top;
        }
        .calculation-table{
            font-size:11px;
        }
        .calculation-table th{
            background:#f3f4f6;
            color:#374151;
        }
        .calculation-table .formula{
            width:180px;
            text-align:left;
            line-height:1.45;
        }
        .calculation-table .result{
            width:70px;
            text-align:center;
            font-weight:600;
            color:#4b5563;
        }
        .checking-note{
            margin-top:22px;
            padding:12px 16px;
            border:2px solid #6b7280;
            background:#f3f4f6;
            color:#374151;
            text-align:center;
            font-size:15px;
            font-weight:700;
            letter-spacing:.2px;
        }

        .meta-table{
            border-collapse:collapse;
            font-size:11px;
        }
        .meta-table td{
            padding:2px 4px;
            border:none;
            vertical-align:top;
        }
        .meta-table .label{
            width:90px;
            font-weight:600;
        }
        .meta-table .colon{
            width:10px;
            text-align:left;
        }

    </style>

    <style>
.compact-table {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
    font-size: 12px;
}

.compact-table th,
.compact-table td {
    border: 1px solid #e2e8f0;
    padding: 4px 6px;
    text-align: center;
    white-space: nowrap;
}

.compact-table th:first-child,
.compact-table td:first-child {
    text-align: left;
    min-width: 160px;
}

/* Diagonal header */
.compact-table th.diagonal {
    height: 80px;
    vertical-align: bottom;
    padding: 0;
}

.compact-table th.diagonal > div {
    transform: rotate(-90deg);
    /*transform-origin: bottom left;*/
    white-space: nowrap;
    font-size: 11px;
    font-weight: 600;
    padding-left:50px;
}

.compact-table thead th {
    background: #f8f9fa;
    font-weight: 700;
}
</style>

</head>

<body onload="window.print()">

    <?php
/* ======================
   COLUMN GROUPING
   ====================== */
   $colLower = [
    'trying','following','calm','composed','sharing','kindness',
    'giving','reminders','upset','reactive','not-sharing','rough'
];
$colMid = [
    'focused','attempting','accepting','recovering','cooperative','listening',
    'distracted','avoiding','rejecting','quitting','disruptive','interrupting'
];
$colUpper = [
    'improving','challenging','steady','resilient','contributing','supporting',
    'resisting','avoidance','overreacting','lingering','withdrawing','dominating'
];

/* ======================
   SELECT COLS BY GRADE
   ====================== */
   if (in_array($class['grade_name'], ['Primary 1','Primary 2'])) {
    $cols = $colLower;
    $finalDescription = $descriptionLower;
    $positiveLabels = ['Trying', 'Following Directions', 'Ready to Learn', 'Self-Control', 'Kind & Respectful', 'Cooperating'];
    $negativeLabels = ['Giving Up', 'Needs Reminders', 'Disrupting Learning', 'Unsafe Behavior', 'Unkind Behavior', 'Not Cooperating'];
} elseif (in_array($class['grade_name'], ['Primary 3','Primary 4'])) {
    $cols = $colMid;
    $finalDescription = $descriptionMid;
    $positiveLabels = ['Focused', 'Attempting', 'Accepting Feedback', 'Recovering', 'Cooperating', 'Listening'];
    $negativeLabels = ['Distracted', 'Avoiding', 'Rejecting', 'Quitting', 'Disruptive', 'Interrupting'];
} else {
    $cols = $colUpper;
    $finalDescription = $descriptionUpper;
    $positiveLabels = ['Self-Motivated', 'Growth Mindset', 'Responsible', 'Respectful', 'Leadership', 'Supportive'];
    $negativeLabels = ['Avoiding Challenges', 'Needs Encouragement', 'Disrupting Learning', 'Poor Self-Control', 'Not Contributing', 'Disrespectful'];
}

$positiveCols = array_merge(array_slice($cols, 0, 6), ['speaking_english', 'prepared', 'organized']);
$negativeCols = array_merge(array_slice($cols, 6), ['needs_english_practice', 'unprepared', 'needs_organization']);
$learningOrganizationLabel = in_array($class['grade_name'], ['Primary 5', 'Primary 6'])
    ? 'Well-Organized'
    : 'Organized';
$positiveLabels = array_merge($positiveLabels, ['Speaking English', 'Prepared', $learningOrganizationLabel]);
$negativeLabels = array_merge($negativeLabels, ['Needs English Practice', 'Unprepared', 'Needs Organization']);

/* ======================
   PRE-CALCULATE HIGHEST
   (FIRST PASS)
   ====================== */
   $highestArea1 = PHP_INT_MIN;
   $highestArea2 = PHP_INT_MIN;
   $highestArea3 = PHP_INT_MIN;
   $highestArea4 = PHP_INT_MIN;

   foreach ($reports as $r) {
    $fin = [0,0,0,0];
    for ($i=0; $i<9; $i++) {
        $group = $i < 6 ? intdiv($i,2) : 3;
        $fin[$group] += (int)$r[$positiveCols[$i]] - (int)$r[$negativeCols[$i]];
    }
    $highestArea1 = max($highestArea1, $fin[0]);
    $highestArea2 = max($highestArea2, $fin[1]);
    $highestArea3 = max($highestArea3, $fin[2]);
    $highestArea4 = max($highestArea4, $fin[3]);
}
?>

<!-- ======================
     SUMMARY (VERY TOP)
====================== -->
<div class="page">

    <div class="header" style="margin-top: 50px;">
        <img src="<?php echo base_url() ?>header.png" style="max-width: 100%;">
        <h3>Socio Emotional Report</h3>
        <div class="meta">
            Class  : <?= esc($class['class_name']) ?><br>
            Grade  : <?= esc($class['grade_name']) ?><br>
            Term : <?= date('m', strtotime($period)) ?>
        </div>
    </div>

    <div class="summary-box">
        <strong>Highest Scores (All Students)</strong><br><br>
        Self Skill : <?= $highestArea1 ?><br>
        Behavior : <?= $highestArea2 ?><br>
        Social : <?= $highestArea3 ?><br>
        Learning Habits : <?= $highestArea4 ?>
    </div>

    <?php 

    // echo json_encode($reports);
    echo "<table class='compact-table'>";
echo "<thead>";
echo "<tr>";
echo "<th class='diagonal' style='width: 150px'>Student Name</th>";

for ($z = 0; $z < count($positiveCols); $z++) {
    echo "<th class='diagonal'><div>{$positiveLabels[$z]}</div></th>";
}

for ($z = 0; $z < count($negativeCols); $z++) {
    echo "<th class='diagonal'><div>{$negativeLabels[$z]}</div></th>";
}

echo "</tr>";
echo "</thead>";
    echo "<tbody>";
    foreach ($reports as $r) {
        echo "<tr>";
        $fin = [0,0,0,0];
        echo "<td style='white-space:normal'>".$r['student_name']."</td>";
        for ($z=0; $z < count($positiveCols); $z++) { 
            echo  "<td>".$r[$positiveCols[$z]]."</td>";
        }

        for ($z=0; $z < count($positiveCols); $z++) { 
            echo  "<td>".$r[$negativeCols[$z]]."</td>";
        }

        for ($i=0; $i<9; $i++) {
            $group = $i < 6 ? intdiv($i,2) : 3;
            $fin[$group] += (int)$r[$positiveCols[$i]] - (int)$r[$negativeCols[$i]];

        }
        $highestArea1 = max($highestArea1, $fin[0]);
        $highestArea2 = max($highestArea2, $fin[1]);
        $highestArea3 = max($highestArea3, $fin[2]);
        $highestArea4 = max($highestArea4, $fin[3]);

        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
     ?>



</div>

<!-- ======================
     STUDENT PAGES
====================== -->
<?php foreach ($reports as $r): ?>

    <?php
    $fin = [0,0,0,0];
    for ($i=0; $i<9; $i++) {
        $group = $i < 6 ? intdiv($i,2) : 3;
        $fin[$group] += (int)$r[$positiveCols[$i]] - (int)$r[$negativeCols[$i]];
    }
    $fin1=$fin[0]; $fin2=$fin[1]; $fin3=$fin[2]; $fin4=$fin[3];
    ?>

    <div class="page">
        <div class="header">
            <img src="<?php echo base_url() ?>header.png" style="max-width: 100%;">
            <h3 style="margin-top:18px;">DETAIL PERHITUNGAN</h3>
            <table class="meta-table" style="max-width: 360px">
                <tr>
                    <td class="label">Student Name</td>
                    <td class="colon">:</td>
                    <td><?= esc($r['student_name']) ?></td>
                </tr>
                <tr>
                    <td class="label">Class</td>
                    <td class="colon">:</td>
                    <td><?= esc($class['class_name']) ?></td>
                </tr>
                <tr>
                    <td class="label">Term</td>
                    <td class="colon">:</td>
                    <td><?= date('m', strtotime($period)) ?></td>
                </tr>
            </table>
        </div>

        <p style="margin:14px 0 8px; color:#4b5563;">
            Nilai akhir dihitung dari jumlah skor positif dikurangi jumlah skor yang perlu dikembangkan. <strong>Highest Score</strong> adalah skor tertinggi pada area tersebut yang digunakan sebagai parameter perbandingan.
        </p>

        <table class="calculation-table">
            <thead>
                <tr>
                    <th style="width:18%;">Area</th>
                    <th>Skor Positif</th>
                    <th>Skor Negatif</th>
                    <th style="width:90px;">Highest Score</th>
                    <th class="formula">Hasil Perhitungan</th>
                    <th class="result">Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $calculationAreas = [
                    ['SELF SKILL', 0, 2, $fin1, $highestArea1],
                    ['BEHAVIOR & DECISION', 2, 4, $fin2, $highestArea2],
                    ['SOCIAL SKILL', 4, 6, $fin3, $highestArea3],
                    ['LEARNING HABITS', 6, 9, $fin4, $highestArea4],
                ];
                foreach ($calculationAreas as [$areaName, $start, $end, $areaResult, $highestScore]):
                    $positiveTotal = 0;
                    $negativeTotal = 0;
                    $positiveFormulaValues = [];
                    $negativeFormulaValues = [];
                    $areaRange = $highestScore / 3;
                    if ($areaResult <= $areaRange) {
                        $calculationCategory = 'DEVELOPING';
                    } elseif ($areaResult <= ($areaRange * 2)) {
                        $calculationCategory = 'CONSISTENTLY DEMONSTRATED';
                    } else {
                        $calculationCategory = 'EXCEEDING EXPECTATIONS';
                    }
                ?>
                    <tr>
                        <td><strong><?= $areaName ?></strong></td>
                        <td>
                            <?php for ($i = $start; $i < $end; $i++):
                                $positiveValue = (int)$r[$positiveCols[$i]];
                                $positiveTotal += $positiveValue;
                                $positiveFormulaValues[] = $positiveValue;
                            ?>
                                <?= esc($positiveLabels[$i]) ?>: <?= $positiveValue ?><br>
                            <?php endfor; ?>
                            <strong>Total: <?= $positiveTotal ?></strong>
                        </td>
                        <td>
                            <?php for ($i = $start; $i < $end; $i++):
                                $negativeValue = (int)$r[$negativeCols[$i]];
                                $negativeTotal += $negativeValue;
                                $negativeFormulaValues[] = $negativeValue;
                            ?>
                                <?= esc($negativeLabels[$i]) ?>: <?= $negativeValue ?><br>
                            <?php endfor; ?>
                            <strong>Total: <?= $negativeTotal ?></strong>
                        </td>
                        <td class="result"><?= $highestScore ?></td>
                        <td class="formula">
                            <small>(<?= implode(' + ', $positiveFormulaValues) ?>)<br>
                            − (<?= implode(' + ', $negativeFormulaValues) ?>)</small><br>
                            <?= $positiveTotal ?> − <?= $negativeTotal ?><br>
                            <strong>= <?= $areaResult ?></strong><br><br>
                            <small>Highest Score ÷ 3<br>
                            <?= $highestScore ?> ÷ 3 = <?= round($areaRange, 2) ?><br>
                            Batas 2 = <?= round($areaRange * 2, 2) ?></small>
                        </td>
                        <td class="result">
                            <?= $calculationCategory ?><br>
                            <small>
                                <?php if ($areaResult <= $areaRange): ?>
                                    <?= $areaResult ?> ≤ <?= round($areaRange, 2) ?>
                                <?php elseif ($areaResult <= ($areaRange * 2)): ?>
                                    <?= round($areaRange, 2) ?> &lt; <?= $areaResult ?> ≤ <?= round($areaRange * 2, 2) ?>
                                <?php else: ?>
                                    <?= $areaResult ?> &gt; <?= round($areaRange * 2, 2) ?>
                                <?php endif; ?>
                            </small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="checking-note">
            CATATAN: Halaman ini hanya untuk checking internal dan bukan untuk dibagikan.
        </div>
    </div>

    <div class="page">

        <div class="header">
            <img src="<?php echo base_url() ?>header.png" style="max-width: 100%;">

            <table class="meta-table" style="max-width: 300px">
                <tr>
                    <td class="label">Student Name</td>
                    <td class="colon">:</td>
                    <td><?= esc($r['student_name']) ?></td>
                </tr>
                <tr>
                    <td class="label">Class</td>
                    <td class="colon">:</td>
                    <td><?= esc($class['class_name']) ?></td>
                </tr>
                <tr>
                    <td class="label">Grade</td>
                    <td class="colon">:</td>
                    <td><?= esc($class['grade_name']) ?></td>
                </tr>
                <tr>
                    <td class="label">Term</td>
                    <td class="colon">:</td>
                    <td><?= date('m', strtotime($period)) ?></td>

                    <?php 



                    ?>
                </tr>
            </table>

        </div>


        <table style="display:none">
            <thead>

                <tr>
                    <?php foreach ($positiveCols as $c): ?>
                        <th><?= strtoupper(str_replace('-', ' ', $c)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($positiveCols as $c): ?>
                        <td><?= (int)$r[$c] ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>

        <table style="display:none">
            <thead>
                <tr>
                    <?php foreach ($negativeCols as $c): ?>
                        <th><?= strtoupper(str_replace('-', ' ', $c)) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach ($negativeCols as $c): ?>
                        <td><?= (int)$r[$c] ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>


        <p style="margin-top:12px; display: none;">
            <strong>Result Scores</strong><br>
            Area 1 : <?= $fin1 ?><br>
            Area 2 : <?= $fin2 ?><br>
            Area 3 : <?= $fin3 ?>
        </p>

       <table>
    <thead>
        <tr><td colspan="4" style="text-align: center;">SOCIAL EMOTIONAL REPORT CARD</td></tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2" rowspan="2"></td>
            <td colspan="2" style="text-align: center;">DESCRIPTION</td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;">
                TERM : <?= date('m', strtotime($period)) ?>
            </td>
        </tr>

        <!-- AREA 1 -->
        <?php 
        $highest = $highestArea1;
        $range = ($highest - 0) / 3;
        $limitA = $range;
        $limitB = $range * 2;

        $value1 = $fin1;

        if ($value1 <= $limitA) {
            $grade1 = 'C';
            $catResult1 = 'DEVELOPING';
            $desc1 = $finalDescription['Life']['C'];
        } elseif ($value1 <= $limitB) {
            $grade1 = 'B';
            $catResult1 = 'CONSISTENTLY DEMONSTRATED';
            $desc1 = $finalDescription['Life']['B'];
        } else {
            $grade1 = 'A';
            $catResult1 = 'EXCEEDING EXPECTATIONS';
            $desc1 = $finalDescription['Life']['A'];
        }
        ?>

        <tr>
            <td>A</td>
            <td><b>SELF SKILL</b>
                <br>Keterampilan Intrapersonal</td>
            <td>CRITERIA<br>Kriteria</td>
            <td><?= $catResult1 ?></td>
        </tr>

        <tr>
            <td></td>
            <td>The student’s ability to manage themselves, demonstrate effort, maintain focus, and develop their personal skills.<br><i>
Kemampuan siswa dalam mengelola diri, berusaha, fokus, dan mengembangkan kemampuan diri.</i></td>
            <td colspan="2"><?= $desc1 ?></td>
        </tr>

        <!-- AREA 3 -->
        <?php 
        $highest = $highestArea2;
        $range = ($highest - 0) / 3;
        $limitA = $range;
        $limitB = $range * 2;

        $value3 = $fin2;

        if ($value3 <= $limitA) {
            $grade3 = 'C';
            $catResult3 = 'DEVELOPING';
            $desc3 = $finalDescription['Dev']['C'];
        } elseif ($value3 <= $limitB) {
            $grade3 = 'B';
            $catResult3 = 'CONSISTENTLY DEMONSTRATED';
            $desc3 = $finalDescription['Dev']['B'];
        } else {
            $grade3 = 'A';
            $catResult3 = 'EXCEEDING EXPECTATIONS';
            $desc3 = $finalDescription['Dev']['A'];
        }
        ?>

        <tr>
            <td>B</td>
            <td><b>BEHAVIOR & DECISION</b>
                <br>Perilaku dan Kemampuan Mengambil Keputusan</td>
            <td>CRITERIA<br>Kriteria</td>
            <td><?= $catResult3 ?></td>
        </tr>

        <tr>
            <td></td>
            <td>The student’s capacity for interaction, collaboration, communication, and conflict resolution.<br><i>
Kemampuan siswa dalam berinteraksi, kerja sama, komunikasi, dan menyelesaikan konflik.</i>
</td>
            <td colspan="2"><?= $desc3 ?></td>
        </tr>

        <!-- AREA 2 -->
        <?php 
        $highest = $highestArea3;
        $range = ($highest - 0) / 3;
        $limitA = $range;
        $limitB = $range * 2;

        $value2 = $fin3;

        if ($value2 <= $limitA) {
            $grade2 = 'A';
            $catResult2 = 'DEVELOPING';
            $desc2 = $finalDescription['Soc']['C'];
        } elseif ($value2 <= $limitB) {
            $grade2 = 'B';
            $catResult2 = 'CONSISTENTLY DEMONSTRATED';
            $desc2 = $finalDescription['Soc']['B'];
        } else {
            $grade2 = 'C';
            $catResult2 = 'EXCEEDING EXPECTATIONS';
            $desc2 = $finalDescription['Soc']['A'];
        }
        ?>

        <tr>
            <td>C</td>
            <td><b>SOCIAL SKILL</b>
                <br>Keterampilan Interpersonal</td>
            <td>CRITERIA<br>Kriteria</td>
            <td><?= $catResult2 ?></td>
        </tr>

        <tr>
            <td></td>
            <td>The student’s behavior in following rules, demonstrating independence, and taking responsibility<br><i>
Perilaku siswa dalam mengikuti aturan, kemandirian, dan tanggung jawab.</i></td>
            <td colspan="2"><?= $desc2 ?></td>
        </tr>

        <!-- AREA 4 -->
        <?php
        $highest = $highestArea4;
        $range = ($highest - 0) / 3;
        $limitA = $range;
        $limitB = $range * 2;
        $value4 = $fin4;

        if ($value4 <= $limitA) {
            $catResult4 = 'DEVELOPING';
            $desc4 = 'The student is developing consistent learning habits and benefits from regular reminders and support.<br><i>Siswa sedang mengembangkan kebiasaan belajar yang konsisten dan masih memerlukan pengingat serta dukungan secara berkala.</i>';
        } elseif ($value4 <= $limitB) {
            $catResult4 = 'CONSISTENTLY DEMONSTRATED';
            $desc4 = 'The student usually communicates appropriately, comes prepared, and keeps learning materials organized with occasional reminders.<br><i>Siswa biasanya berkomunikasi dengan baik, membawa perlengkapan, dan menjaga kerapian dengan sesekali pengingat.</i>';
        } else {
            $catResult4 = 'EXCEEDING EXPECTATIONS';
            $desc4 = 'The student consistently uses English, comes prepared, and keeps learning materials organized independently.<br><i>Siswa secara konsisten menggunakan bahasa Inggris, membawa perlengkapan, dan menjaga kerapian secara mandiri.</i>';
        }
        ?>

        <tr>
            <td>D</td>
            <td><b>LEARNING HABITS</b>
                <br>Kebiasaan dan Kesiapan Belajar</td>
            <td>CRITERIA<br>Kriteria</td>
            <td><?= $catResult4 ?></td>
        </tr>

        <tr>
            <td></td>
            <td>The student’s ability to communicate appropriately in English, prepare learning materials, and keep personal belongings organized.<br><i>
            Kemampuan siswa berkomunikasi dalam bahasa Inggris, menyiapkan perlengkapan belajar, dan menjaga kerapian barang pribadi.</i></td>
            <td colspan="2"><?= $desc4 ?></td>
        </tr>

    </tbody>
</table>
        <br>

        <div class="signature" style="display:none">
            Teacher<br><br><br>
            ( ____________________ )
        </div>

    </div>

<?php endforeach; ?>

</body>
</html>
