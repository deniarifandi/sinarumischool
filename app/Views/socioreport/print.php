
<?php // CLASS -> CATEGORY -> RESULT -> DESCRIPTION
$finalDescription;
$descriptionLower = [
    'Life' => [
        'A' => 
        'Shows strong self-awareness and self-regulation; calms self and works independently.<br>
        Menunjukkan kesadaran diri dan regulasi diri yang kuat; mampu bersikap tenang dan bekerja secara mandiri',
        'B' => 'Recognises feelings and manages reactions with appropriate support; follows routines. <br> Menunjukkan kemampuan mengenali perasaan dengan dukungan yang sesuai, dan secara konsisten mengikuti rutinitas.',
        'C' => 'Beginning to identify feelings; needs regular reminders for regulation and focus.<br>Menunjukkan perkembangan awal dalam mengenali perasaan dan masih memerlukan pendampingan untuk regulasi emosi dan fokus.',
    ],
    'Soc' => [
        'A' => 'Consistently empathetic, leads positive peer interactions and resolves minor conflicts.<br>
        Secara konsisten menunjukkan empati, memimpin interaksi positif dengan teman sebaya, serta mampu menyelesaikan konflik ringan.',
        'B' => 'Interacts respectfully, shows empathy, cooperates in groups with occasional reminders.<br>
        Berinteraksi dengan sopan, menunjukkan empati, serta bekerja sama dalam kelompok dengan sesekali pengingat.',
        'C' => 'Starting to show empathy; needs guidance in sharing and turn-taking.<br>
        Mulai menunjukkan empati; masih memerlukan bimbingan dalam berbagi dan bergiliran.',
    ],
    'Dev' => [
        'A' => 'Makes thoughtful decisions, demonstrates responsibility, and guides peers.<br>
        Membuat keputusan secara bijaksana, menunjukkan tanggung jawab, dan membimbing teman sebaya.',
        'B' => 'Makes safe choices, accepts consequences, and learns from feedback.<br>
        Membuat pilihan yangbertanggungjawab, menerima konsekuensi, dan belajar dari umpan balik',
        'C' => 'Sometimes makes impulsive choices; needs support to consider consequences.<br>
        Kadang menunjukkan keputusan impulsif dan membutuhkan bimbingan untuk memahami serta mempertimbangkan konsekuensi.',
    ]
];
$descriptionMid = [
    'Life' => [
        'A' => 'Shows strong self-awareness and self-regulation; calms self and works independently.<br>
        Menunjukkan kesadaran diri dan regulasi diri yang kuat; mampu bersikap tenang dan bekerja secara mandiri',
        'B' => 'Recognises feelings and manages reactions with appropriate support; follows routines.<br>
        Menunjukkan kemampuan mengenali perasaan dengan dukungan yang sesuai, dan secara konsisten mengikuti rutinitas.',
        'C' => 'Beginning to identify feelings; needs regular reminders for regulation and focus.<br>
        Menunjukkan perkembangan awal dalam mengenali perasaan dan masih memerlukan pendampingan untuk regulasi emosi dan fokus.',
    ],
    'Soc' => [
        'A' => 'Consistently empathetic, leads positive peer interactions and resolves minor conflicts.<br>
        Secara konsisten menunjukkan empati, memimpin interaksi positif dengan teman sebaya, serta mampu menyelesaikan konflik ringan.',
        'B' => 'Interacts respectfully, shows empathy, cooperates in groups with occasional reminders.<br>
        Berinteraksi dengan sopan, menunjukkan empati, serta bekerja sama dalam kelompok dengan sesekali pengingat.',
        'C' => 'Starting to show empathy; needs guidance in sharing and turn-taking.<br>
        Mulai menunjukkan empati; masih memerlukan bimbingan dalam berbagi dan bergiliran.',
    ],
    'Dev' => [
        'A' => 'Makes thoughtful decisions, demonstrates responsibility, and guides peers.<br>
Membuat keputusan secara bijaksana, menunjukkan tanggung jawab, dan membimbing teman sebaya.',
        'B' => 'Makes safe choices, accepts consequences, and learns from feedback.<br>
Membuat pilihan yangbertanggungjawab, menerima konsekuensi, dan belajar dari umpan balik',
        'C' => 'Sometimes makes impulsive choices; needs support to consider consequences.<br>
Kadang menunjukkan keputusan impulsif dan membutuhkan bimbingan untuk memahami serta mempertimbangkan konsekuensi.',
    ]
];
$descriptionUpper = [
    'Life' => [
        'A' => 'Shows strong self-awareness and self-regulation; calms self and works independently.<br>Menunjukkan kesadaran diri dan regulasi diri yang kuat; mampu bersikap tenang dan bekerja secara mandiri',
        'B' => 'Recognises feelings and manages reactions with appropriate support; follows routines. <br>Menunjukkan kemampuan mengenali perasaan dengan dukungan yang sesuai, dan secara konsisten mengikuti rutinitas.',
        'C' => 'Beginning to identify feelings; needs regular reminders for regulation and focus. <br> Menunjukkan perkembangan awal dalam mengenali perasaan dan masih memerlukan pendampingan untuk regulasi emosi dan fokus.',
    ],
    'Soc' => [
        'A' => 'Consistently empathetic, leads positive peer interactions and resolves minor conflicts.<br> Secara konsisten menunjukkan empati, memimpin interaksi positif dengan teman sebaya, serta mampu menyelesaikan konflik ringan.',
        'B' => 'Interacts respectfully, shows empathy, cooperates in groups with occasional reminders.<br> Berinteraksi dengan sopan, menunjukkan empati, serta bekerja sama dalam kelompok dengan sesekali pengingat.',
        'C' => 'Starting to show empathy; needs guidance in sharing and turn-taking.<br>
Mulai menunjukkan empati; masih memerlukan bimbingan dalam berbagi dan bergiliran.',
    ],
    'Dev' => [
        'A' => 'Makes thoughtful decisions, demonstrates responsibility, and guides peers.
<br>Membuat keputusan secara bijaksana, menunjukkan tanggung jawab, dan membimbing teman sebaya.',
        'B' => 'Makes safe choices, accepts consequences, and learns from feedback.<br>Membuat pilihan yangbertanggungjawab, menerima konsekuensi, dan belajar dari umpan balik',
        'C' => 'Sometimes makes impulsive choices; needs support to consider consequences.
<br>Kadang menunjukkan keputusan impulsif dan membutuhkan bimbingan untuk memahami serta mempertimbangkan konsekuensi.',
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
    'giving','reminders','upset','reactive','not-sharing','rough',
];
$colMid = [
    'focused','attempting','accepting','recovering','cooperative','listening',
    'distracted','avoiding','rejecting','quitting','disruptive','interrupting',
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
} elseif (in_array($class['grade_name'], ['Primary 3','Primary 4'])) {
    $cols = $colMid;
    $finalDescription = $descriptionMid;
} else {
    $cols = $colUpper;
    $finalDescription = $descriptionUpper;
}

$positiveCols = array_slice($cols, 0, 6);
$negativeCols = array_slice($cols, 6);

/* ======================
   PRE-CALCULATE HIGHEST
   (FIRST PASS)
   ====================== */
   $highestArea1 = PHP_INT_MIN;
   $highestArea2 = PHP_INT_MIN;
   $highestArea3 = PHP_INT_MIN;

   foreach ($reports as $r) {
    $fin = [0,0,0];
    for ($i=0; $i<6; $i++) {
        $group = intdiv($i,2);
        $fin[$group] += (int)$r[$positiveCols[$i]] - (int)$r[$negativeCols[$i]];
    }
    $highestArea1 = max($highestArea1, $fin[0]);
    $highestArea2 = max($highestArea2, $fin[1]);
    $highestArea3 = max($highestArea3, $fin[2]);
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
        Area 1 : <?= $highestArea1 ?><br>
        Area 2 : <?= $highestArea2 ?><br>
        Area 3 : <?= $highestArea3 ?>
    </div>

    <?php 

    // echo json_encode($reports);
    echo "<table class='compact-table'>";
echo "<thead>";
echo "<tr>";
echo "<th class='diagonal' style='width: 150px'>Student Name</th>";

for ($z = 0; $z < count($positiveCols); $z++) {
    echo "<th class='diagonal'><div>{$positiveCols[$z]}</div></th>";
}

for ($z = 0; $z < count($negativeCols); $z++) {
    echo "<th class='diagonal'><div>{$negativeCols[$z]}</div></th>";
}

echo "</tr>";
echo "</thead>";
    echo "<tbody>";
    foreach ($reports as $r) {
        echo "<tr>";
        $fin = [0,0,0];
        echo "<td style='white-space:normal'>".$r['student_name']."</td>";
        for ($z=0; $z < count($positiveCols); $z++) { 
            echo  "<td>".$r[$positiveCols[$z]]."</td>";
        }

        for ($z=0; $z < count($positiveCols); $z++) { 
            echo  "<td>".$r[$negativeCols[$z]]."</td>";
        }

        for ($i=0; $i<6; $i++) {
            $group = intdiv($i,2);
            $fin[$group] += (int)$r[$positiveCols[$i]] - (int)$r[$negativeCols[$i]];

        }
        $highestArea1 = max($highestArea1, $fin[0]);
        $highestArea2 = max($highestArea2, $fin[1]);
        $highestArea3 = max($highestArea3, $fin[2]);

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
    $fin = [0,0,0];
    for ($i=0; $i<6; $i++) {
        $group = intdiv($i,2);
        $fin[$group] += (int)$r[$positiveCols[$i]] - (int)$r[$negativeCols[$i]];
    }
    $fin1=$fin[0]; $fin2=$fin[1]; $fin3=$fin[2];
    ?>

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
                    <td colspan="2"  style="text-align: center;">DESCRIPTION</td>
                </tr>

                <tr>
                    <td colspan="2"  style="text-align: center;">TERM : <?= date('m', strtotime($period)) ?></td>
                </tr>
                <tr>
                       <?php 
                     $highest = $highestArea1;
                     $lowest  = 0;

                     $range = ($highest - $lowest) / 3;

                     $limitA = $lowest + $range;
                     $limitB = $lowest + ($range * 2);

                        $value = $fin1; // value to check

                        if ($value <= $limitA) {
                            $category = 'C';
                            $catResult = 'DEVELOPING <br> Berkembang';
                            $category = $finalDescription['Life']['C'];
                        } elseif ($value <= $limitB) {
                            $category = 'B';
                            $catResult = 'CONSISTENT <br> Baik';
                            $category = $finalDescription['Life']['B'];
                        } else {
                            $category = 'A';
                            $catResult = 'ADVANCE <br> Sangat Baik';
                            $category = $finalDescription['Life']['A'];
                        }
                        //echo $value;

                        ?>

                    <td>A</td>
                    <td>SELF SKILL<br>
                        Keterampilan Intrapersonal
                    </td>
                    <td>
                        CRITERIA<br>
                        Kriteria
                    </td>
                    <td>
                        <?php echo $catResult; ?>
                    </td>
                </tr>
                <tr>
                    <?php 
                     $highest = $highestArea2;
                     $lowest  = 0;

                     $range = ($highest - $lowest) / 3;

                     $limitA = $lowest + $range;
                     $limitB = $lowest + ($range * 2);

                        $value = $fin2; // value to check

                        if ($value <= $limitA) {
                            $category = 'C';
                            $catResult = 'DEVELOPING <br> Berkembang';
                            $category = $finalDescription['Soc']['C'];
                        } elseif ($value <= $limitB) {
                            $category = 'B';
                            $catResult = 'CONSISTENT <br> Baik';
                            $category = $finalDescription['Soc']['B'];
                        } else {
                            $category = 'A';
                            $catResult = 'ADVANCE <br> Sangat Baik';
                            $category = $finalDescription['Soc']['A'];
                        }
                        //echo $value;
                        //echo $category;
                        ?>

                    <td></td>
                    <td>The student’s ability to manage themselves, demonstrate effort, maintain focus, and develop their personal skills.<br>
                        Kemampuan siswa dalam mengelola diri, berusaha, fokus, dan mengembangkan kemampuan diri.
                        <br><br>
                        Lower Level (P1-P2)<br>
                        Scope : <br>
                        - Trying<br>
                        Tetap berusaha saat tugas sulit.<br>
                        - Following<br>
                        mengikuti instruksi sederhana dengan usaha.<br>
                    </td>
                    <td colspan="2">
                  
                        <?php echo $category;  ?>

                    </td>
                    
                </tr>
                <tr>
                    <td>B</td>
                    <td>SOCIAL SKILL<br>
                        Keterampilan Interpersonal
                    </td>
                    <td>
                        CRITERIA<br>
                        Kriteria
                    </td>
                    <td>
                        <?php echo $catResult; ?>
                    </td>
                </tr>
                <tr>


                     <?php 
                     $highest = $highestArea3;
                     $lowest  = 0;

                     $range = ($highest - $lowest) / 3;

                     $limitA = $lowest + $range;
                     $limitB = $lowest + ($range * 2);

                        $value = $fin3; // value to check

                         if ($value <= $limitA) {
                            $category = 'C';
                            $catResult = 'DEVELOPING <br> Berkembang';
                            $category = $finalDescription['Dev']['C'];
                        } elseif ($value <= $limitB) {
                            $category = 'B';
                            $catResult = 'CONSISTENT <br> Baik';
                            $category = $finalDescription['Dev']['B'];
                        } else {
                            $category = 'A';
                            $catResult = 'ADVANCE <br> Sangat Baik';
                            $category = $finalDescription['Dev']['A'];
                        }
                        
                        ?>
                    <td></td>
                    <td>The student’s behavior in following rules, demonstrating independence, and taking responsibility<br>
                        Perilaku siswa dalam mengikuti aturan, kemandirian, dan tanggung jawab.<br>
                        <br>
                        Lower Level (P1-P2)<br>
                        Scope : <br>
                        - Sharing<br>
                        Mau berbagi alat/bahan.<br>
                        - Kindness <br>
                        Bekerja dan bermain dengan ramah.<br>
                    </td>
                    <td colspan="2">

                        <?php echo $category ?>

                    </td>
                </tr>

                <tr>
                    <td>C</td>
                    <td>BEHAVIOR & DECISION<br>
                        Perilaku dan Kemampuan Mengambil Keputusan
                    </td>
                    <td>
                        CRITERIA<br>
                        Kriteria
                    </td>
                    <td>
                        <?php echo $catResult; ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>The student’s capacity for interaction, collaboration, communication, and conflict resolution.<br>
                        Kemampuan siswa dalam berinteraksi, kerja sama, komunikasi, dan menyelesaikan konflik.<br>
                        <br>
                        Lower Level (P1-P2)<br>
                        Scope : <br>
                        - Calm<br>
                        Cepat tenang setelah konflik kecil.<br>
                        - Composed<br>
                        Tetap tenang saat ditegur.<br>

                    </td>
                    <td colspan="2">

                        <?php echo $category; ?>

                    </td>
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
