<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Academic Chapter List</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* -------------------- General Styles -------------------- */
body {
    font-family: Arial, sans-serif;
}
.container {
    max-width: 992px;
    margin-top: 2rem;
}

/* -------------------- Header Styles -------------------- */
.header {
    display: flex;
    justify-content: center;
    align-items: center;
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
    margin-bottom: 30px;
}
.header img {
    height: 60px;
    width: auto;
    margin-right: 10px;
}
.header h1 {
    font-weight: 700;
    font-size: 1.8rem;
    color: #343a40;
    margin: 0;
}

/* -------------------- Table Styles -------------------- */
.chapter-table {
    margin-top: 15px;
    border: 1px solid #dee2e6;
}
.chapter-table thead th {
    background-color: #e9ecef;
    color: #343a40;
    border-bottom: 2px solid #dee2e6;
}
.table-grade-header {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    font-size: 1.1rem;
    padding: 8px 15px;
}
.table-subject-header {
    background-color: #f8f9fa;
    font-weight: bold;
    text-transform: uppercase;
    color: #495057;
    border-top: 1px solid #dee2e6;
}

/* Indentation */
.ps-unit { padding-left: 2rem; }
.ps-subunit { padding-left: 3.5rem; }
.ps-objective { padding-left: 5rem; }

/* Row styles */
.row-unit { font-weight: bold; }
.row-subunit { font-style: italic; color: #495057; }
.row-objective { font-size: 0.9rem; color: #6c757d; }

/* -------------------- Print Styles -------------------- */
@media print {
    body { font-size: 10pt; margin: 0; padding: 0; }
    .container { width: 100%; max-width: none; }
    .header { border-bottom: 2px solid #000; margin-bottom: 15px; }
    .header img { height: 50px; }
    .header h1 { font-size: 1.5rem; color: #000; }
    .chapter-table, .chapter-table th, .chapter-table td { border-color: #ccc !important; }
    .table-grade-header { background-color: #adb5bd !important; color: #000 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .table-subject-header { background-color: #e9ecef !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
</head>
<body>

<div class="container">

    <!-- Single Header -->
    <div class="header">
        <img src="<?= base_url(); ?>sdlogo.png" alt="Logo">
        <h1>Academic Chapter List</h1>
    </div>

    <?php
    $currentGrade = '';
    $currentSubject = '';
    $currentUnit = '';
    $currentSubunit = '';
    $currentObjective = '';

    foreach ($data as $row) {

        // New Grade
        if ($currentGrade != $row->tingkat_id) {
            if ($currentGrade != '') echo "</tbody></table>";
            
            echo "<div class='table-grade-header'>GRADE {$row->tingkat_id}</div>";
            echo "<table class='table table-sm table-bordered chapter-table'>";
            echo "<thead><tr><th>Chapter Component</th><th width='120' class='text-center'>JP</th></tr></thead>";
            echo "<tbody>";
            
            $currentGrade = $row->tingkat_id;
            $currentSubject = '';
        }

        // New Subject
        if ($currentSubject != $row->subjek_nama) {
            echo "<tr class='table-subject-header'><td colspan='2' class='ps-3'>{$row->subjek_nama}</td></tr>";
            $currentSubject = $row->subjek_nama;
            $currentUnit = '';
        }

        // New Unit
        if ($currentUnit != $row->unit_nama) {
            echo "<tr class='row-unit'><td class='ps-unit'>{$row->unit_nama}</td><td class='text-center'><b>{$row->unit_jp}</b></td></tr>";
            $currentUnit = $row->unit_nama;
            $currentSubunit = '';
        }

        // New Subunit
        if ($currentSubunit != $row->subunit_nama) {
            echo "<tr class='row-subunit'><td class='ps-subunit'>– {$row->subunit_nama}</td><td class='text-center'>{$row->subunit_jp}</td></tr>";
            $currentSubunit = $row->subunit_nama;
            $currentObjective = '';
        }

        // Objective
        if (!empty($row->tujuan_nama) && $currentObjective != $row->tujuan_nama) {
            echo "<tr class='row-objective'><td class='ps-objective'>&bull; {$row->tujuan_nama}</td><td></td></tr>";
            $currentObjective = $row->tujuan_nama;
        }
    }

    if ($currentGrade != '') echo "</tbody></table>";
    ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
