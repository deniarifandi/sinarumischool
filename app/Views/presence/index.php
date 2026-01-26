<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<!-- TODAY ATTENDANCE -->
<div class="glass-card">

  <h5 class="mb-4 fw-bold text-primary">Today's Attendance</h5>

  <?php if (session('success')): ?>
    <div class="alert alert-success border-0 shadow-sm">
      <?= session('success') ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($presence)): ?>

    <!-- ALREADY CHECKED -->
    <div class="text-center py-3">
      <i class="bi bi-check-circle-fill text-success fs-1 mb-2"></i>
      <p class="text-muted mb-3">You have already checked in today.</p>
      <button class="btn btn-outline-secondary w-100" disabled>
        Checked In
      </button>
    </div>

  <?php else: ?>

    <!-- CHECK-IN FORM -->
    <form method="post" action="<?= base_url('presence/checkin') ?>" id="attendanceForm">

      <!-- STATUS -->
      <div class="mb-4">
        <label class="form-label small text-uppercase fw-bold text-muted">
          Attendance Status
        </label>

        <div class="btn-group w-100" role="group">
          <?php
            $statuses = [1 => 'Hadir', 2 => 'Izin', 3 => 'Sakit'];
            foreach ($statuses as $key => $label):
          ?>
            <input
              type="radio"
              class="btn-check"
              name="status"
              id="status<?= $key ?>"
              value="<?= $key ?>"
              <?= $key === 1 ? 'checked' : '' ?>
              required
            >
            <label class="btn btn-outline-primary" for="status<?= $key ?>">
              <?= $label ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- LOCATION -->
      <div class="bg-light rounded p-3 mb-4 border">

        <div class="d-flex align-items-center mb-2">
          <div id="locSpinner" class="spinner-grow spinner-grow-sm text-primary me-2"></div>
          <span class="small fw-bold text-muted text-uppercase">
            Current Location
          </span>
        </div>

        <div class="small">
          <div class="d-flex justify-content-between">
            <span class="text-muted">Coordinate:</span>
            <span id="coordText" class="font-monospace">Detecting…</span>
          </div>

          <hr class="my-1 opacity-25">

          <div id="locText" class="fw-medium text-dark">
            Fetching address…
          </div>
        </div>

        <div id="map" class="rounded border mt-3" style="height:260px;"></div>
      </div>

      <!-- HIDDEN -->
      <input type="hidden" name="latitude" id="lat">
      <input type="hidden" name="longitude" id="lng">

      <!-- SUBMIT -->
      <button
        type="submit"
        id="submitBtn"
        class="btn btn-primary w-100 py-2 fw-bold"
        disabled
      >
        Submit Attendance
      </button>

    </form>
  <?php endif; ?>

</div>

<!-- RECENT HISTORY -->
<div class="glass-card">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0 fw-bold text-secondary text-uppercase">
      Recent History
    </h6>
    <span class="badge bg-light text-dark border">
      <?= count($history) ?> Records
    </span>
  </div>

  <?php if (!empty($history)): ?>

    <?php foreach ($history as $h): ?>
      <div class="attendance-item d-flex justify-content-between align-items-center py-3 border-bottom">

        <div class="d-flex align-items-center">
          <div class="date-circle me-3 text-center">
            <strong class="text-primary">
              <?= date('d', strtotime($h['presensidata_tanggal'])) ?>
            </strong>
            <div class="small text-muted text-uppercase">
              <?= date('M', strtotime($h['presensidata_tanggal'])) ?>
            </div>
          </div>

          <div>
            <div class="fw-bold text-primary" style="font-size:.9rem">
              <?= date('Y', strtotime($h['presensidata_tanggal'])) ?>
            </div>
            <small class="text-muted">
              <i class="bi bi-clock"></i> Checked in
            </small>
          </div>
        </div>

        <?php
          $statusClass = [
            1 => 'success',
            2 => 'warning',
            3 => 'danger'
          ];
          $statusLabel = [
            1 => 'Hadir',
            2 => 'Izin',
            3 => 'Sakit'
          ];
        ?>
        <span class="badge rounded-pill px-3 py-2
              bg-<?= $statusClass[$h['status']] ?>-subtle
              text-<?= $statusClass[$h['status']] ?>
              border border-<?= $statusClass[$h['status']] ?>">
          <?= $statusLabel[$h['status']] ?>
        </span>

      </div>
    <?php endforeach; ?>

    <?php if ($totalPages > 1): ?>
      <nav class="mt-3">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>">Prev</a>
          </li>
          <li class="page-item disabled">
            <span class="page-link">
              <?= $page ?> / <?= $totalPages ?>
            </span>
          </li>
          <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>

  <?php else: ?>
    <p class="text-muted text-center small py-3 mb-0">
      No attendance records found.
    </p>
  <?php endif; ?>

  <div class="mt-3 text-center">
    <a
      href="<?= base_url('presence/full_report/' . date('Y/m')) ?>"
      class="btn btn-primary btn-sm"
    >
      View Full Report <i class="bi bi-chevron-right ms-1"></i>
    </a>
  </div>

</div>

<?= $this->endSection() ?>
