<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sub-Chapter List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid my-4">

  <!-- Header Logo -->
  <div class="text-center mb-4">
    <img src="<?= base_url(); ?>sdlogo.png" alt="Logo" style="max-width: 200px;">
  </div>

  <!-- Page Title -->
  <div class="text-center mb-4">
    <h2>Sub-Chapter List</h2>
  </div>

  <!-- Table -->
  <table class="table table-bordered table-striped table-hover w-100">
    <thead class="table-primary text-center">
      <tr>
        <th>Primary Chapter / Unit / Subunit</th>
        <th>Grade</th>
        <th>Teaching Hours (JP)</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($data as $row) {
          $grade = $row->tingkat_id ?? '-';
          $subject = $row->subjek_nama ?? '-';
          $unit = $row->unit_nama ?? '-';
          $subunit = $row->subunit_nama ?? '-';
          $unit_jp = $row->unit_jp ?? '-';
          $subunit_jp = $row->subunit_jp ?? '-';

          // Primary Chapter / Unit
          echo "<tr class='table-secondary'><td colspan='3'>{$subject} - {$unit} (JP: {$unit_jp})</td></tr>";

          // Subunit row
          echo "<tr>";
          echo "<td>- {$subunit}</td>";
          echo "<td class='text-center'>{$grade}</td>";
          echo "<td class='text-center'>{$subunit_jp}</td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
