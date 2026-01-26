<?= $this->extend('./main') ?>
<?= $this->section('content') ?>

<?php
$currentDate = strtotime("$year-$month-01");
$prevDate    = strtotime("-1 month", $currentDate);
$nextDate    = strtotime("+1 month", $currentDate);

$prevMonth   = date('m', $prevDate);
$prevYear    = date('Y', $prevDate);
$nextMonth   = date('m', $nextDate);
$nextYear    = date('Y', $nextDate);

$daysInMonth = cal_days_in_month(CAL_GREGORIAN, (int)$month, (int)$year);
$firstDay    = (int)date('w', $currentDate);

$statusMap = [
    1 => ['label' => 'Hadir', 'class' => 'status-hadir'],
    2 => ['label' => 'Izin',  'class' => 'status-izin'],
    3 => ['label' => 'Sakit', 'class' => 'status-sakit'],
];

$stats = array_count_values($attendance ?? []);
?>

<div class="attendance-card card border-0 shadow-sm " style="margin-bottom: 50px">

  <div class="card-header bg-white border-0 p-4">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="fw-bold mb-0 text-dark">
          <?= date('F', $currentDate) ?>
          <span class="text-muted fw-light"><?= $year ?></span>
        </h4>
        <p class="text-muted small mb-0">Monthly Attendance</p>
      </div>
      <div class="btn-group shadow-sm border rounded-pill overflow-hidden">
        <a href="<?= base_url("presence/full_report/$prevYear/$prevMonth") ?>" class="btn btn-white border-0">
          <i class="bi bi-chevron-left text-muted"></i>
        </a>
        <a href="<?= base_url("presence/full_report/".date('Y/m')) ?>" class="btn btn-white border-0 small fw-bold px-2">
          Current
        </a>
        <a href="<?= base_url("presence/full_report/$nextYear/$nextMonth") ?>" class="btn btn-white border-0">
          <i class="bi bi-chevron-right text-muted"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="px-4 pb-3">
    <div class="row g-3">
      <?php foreach ($statusMap as $id => $map): ?>
        <div class="col-4">
          <div class="stat-pill p-2 rounded-3 text-center border">
            <div class="small text-muted <?= $map['class'] ?>"><?= $map['label'] ?></div>
            <div class="h5 mb-0 fw-bold"><?= $stats[$id] ?? 0 ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="table-responsive px-2 pb-4 calendar-scroll-fix">
    <table class="table table-borderless mb-0 calendar-table">
      <thead>
        <tr class="text-center text-muted small fw-bold">
          <th class="text-danger-emphasis">SUN</th>
          <th>MON</th><th>TUE</th><th>WED</th>
          <th>THU</th><th>FRI</th>
          <th class="text-danger-emphasis">SAT</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $day = 1;
        echo '<tr>';

        for ($i = 0; $i < $firstDay; $i++) {
          echo '<td class="empty-cell"></td>';
        }

        for ($cell = $firstDay; $cell < 42; $cell++) {
          if ($day <= $daysInMonth) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $isToday = $dateStr === date('Y-m-d') ? 'is-today' : '';
            $status  = $attendance[$dateStr] ?? null;
            $class   = $status ? $statusMap[$status]['class'] : '';

            echo "<td class='calendar-day-cell'>";
            echo "<div class='day-wrapper $isToday $class'>";
            echo "<span class='day-number'>$day</span>";
            if ($status) echo "<div class='status-dot'></div>";
            echo "</div></td>";

            $day++;
          } else {
            echo '<td class="empty-cell"></td>';
          }

          if ((($cell + 1) % 7) === 0 && $cell < 41) {
            echo '</tr><tr>';
          }
        }
        echo '</tr>';
        ?>
      </tbody>
    </table>
  </div>

</div>

<style>
/* ===== SCROLL & REFRESH FIX ===== */
html, body {
  overscroll-behavior-y: contain;
}

.calendar-scroll-fix {
  overscroll-behavior: contain;
}

.calendar-table {
  touch-action: pan-y;
}

/* ===== UI ===== */
.attendance-card { border-radius: 20px; }
.btn-white { background:#fff; }
.btn-white:hover { background:#f8f9fa; }

.calendar-table { min-width:320px; table-layout:fixed; }
.calendar-table th { font-size:.65rem; padding-bottom:14px; }

.calendar-day-cell { height:56px; text-align:center; }

.day-wrapper{
  width:38px;
  height:38px;
  margin:auto;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  position:relative;
}

.day-number{ font-size:.9rem; }

.is-today{
  background:#eff6ff;
  border:3px solid #3b82f6;
}

.status-dot{
  width:5px;
  height:5px;
  border-radius:50%;
  position:absolute;
  bottom:5px;
}

.status-hadir{ background:#dcfce7; }
.status-hadir .day-number{ color:#15803d; }
.status-hadir .status-dot{ background:#15803d; }

.status-izin{ background:#fef9c3; }
.status-izin .day-number{ color:#a16207; }
.status-izin .status-dot{ background:#a16207; }

.status-sakit{ background:#fee2e2; }
.status-sakit .day-number{ color:#b91c1c; }
.status-sakit .status-dot{ background:#b91c1c; }

.empty-cell{ pointer-events:none; }

.stat-pill{ background:#fff; }
</style>

<?= $this->endSection() ?>
