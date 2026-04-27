<?= $this->extend('./main') ?>
<?= $this->section('content') ?>

<div class="glass-card p-4">
    <h5 class="mb-4 fw-bold text-primary">Today's Attendance</h5>

    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm"><?= session('success') ?></div>
    <?php endif; ?>

    <?php if ($presence): ?>

        <div class="text-center py-3">
            <div class="text-success mb-2">
                <i class="fas fa-check-circle fa-3x"></i>
            </div>
            <p class="text-muted">You have already checked in today.</p>
            <button class="btn btn-outline-secondary w-100" disabled>Checked In</button>
        </div>
    <?php else: ?>

        <form method="post" action="<?= base_url('presence/checkin') ?>" id="attendanceForm">
            <div class="mb-4">
                <label class="form-label small text-uppercase fw-bold text-muted">Attendance Status</label>
                <div class="btn-group w-100" role="group" aria-label="Attendance Status">
                    <input type="radio" class="btn-check" name="status" id="status1" value="1" autocomplete="off" checked required>
                    <label class="btn btn-outline-primary" for="status1">Hadir</label>

                    <input type="radio" class="btn-check" name="status" id="status2" value="2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="status2">Izin</label>

                    <input type="radio" class="btn-check" name="status" id="status3" value="3" autocomplete="off">
                    <label class="btn btn-outline-primary" for="status3">Sakit</label>
                </div>
            </div>

            <div class="bg-light rounded p-3 mb-4 border">
                
                <div class="d-flex align-items-center mb-2">
                    <div class="spinner-grow spinner-grow-sm text-primary me-2" role="status" id="locSpinner"></div>
                    <span class="small fw-bold text-muted uppercase">Current Location</span>
                </div>
                
               <!--  <div class="small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Coordinate:</span>
                        <span id="coordText" class="font-monospace">Detecting...</span>
                    </div>
                    <hr class="my-1 opacity-25">
                    <div class="text-dark fw-medium" id="locText">Fetching address details...</div>
                </div> -->

                <div class="small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Coordinate:</span>
                        <span id="coordText" class="font-monospace">Detecting...</span>
                    </div>

                    <hr class="my-1 opacity-25">

                    <div>
                        <span class="text-muted d-block">Detected Address:</span>
                        <div id="locText" class="fw-medium text-dark">
                            Fetching address details...
                        </div>
                    </div>
                </div>
                <div id="map" class="rounded border mb-4" style="height:260px;"></div>
            </div>

            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lng">
            <input type="hidden" name="address" id="address">

            <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                Submit Attendance
            </button>
        </form>

    <?php endif; ?>
</div>

<div class="glass-card p-4" style="">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold text-secondary text-uppercase" style="letter-spacing: 1px;">
            Recent History
        </h6>
        <span class="badge bg-light text-dark border"><?= count($history) ?> Records</span>
    </div>

    <div class="attendance-list">
        <?php if (!empty($history)): ?>
            <?php foreach($history as $h): ?>
                <div class="attendance-item d-flex align-items-center justify-content-between py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="date-circle me-3 text-center">
                            <span class="d-block fw-bold text-primary mb-0">
                                <?= date('d', strtotime($h['presensidata_tanggal'])) ?>
                            </span>
                            <span class="small text-muted text-uppercase" style="font-size: 0.7rem;">
                                <?= date('M', strtotime($h['presensidata_tanggal'])) ?>
                            </span>
                        </div>
                        
                        <div>
                            <div class="fw-bold mb-0 text-primary" style="font-size: 0.9rem;">
                                <?= date('Y', strtotime($h['presensidata_tanggal'])) ?>
                            </div>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i> Checked in
                            </small>
                        </div>
                    </div>

                    <div class="text-end">
                        <?php
$statusClass =
    $h['status'] == 1 ? 'bg-success-subtle text-success border border-success' :
    ($h['status'] == 2 ? 'bg-warning-subtle text-warning-emphasis border border-warning' :
    ($h['status'] == 3 ? 'bg-danger-subtle text-danger border border-danger' :
    'bg-info-subtle text-info border border-info'));

$statusText =
    $h['status'] == 1 ? 'Hadir' :
    ($h['status'] == 2 ? 'Izin' :
    ($h['status'] == 3 ? 'Sakit' : 'Null'));
?>

<span class="badge rounded-pill px-3 py-2 <?= $statusClass ?>">
    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
    <?= $statusText ?>
</span>

                    </div>
                </div>
            <?php endforeach; ?>

          <?php if ($totalPages > 1): ?>
<nav>
  <ul class="pagination justify-content-center">

    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $page - 1 ?>">Prev</a>
    </li>

    <li class="page-item disabled">
      <span class="page-link">
        Page <?= $page ?> / <?= $totalPages ?>
      </span>
    </li>

    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
    </li>

  </ul>
</nav>

<?php endif; ?>



        <?php else: ?>
            <div class="text-center py-4">
                <p class="text-muted small mb-0">No attendance records found.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="mt-3 text-center">
    <a href="<?= base_url('presence/full_report/' . date('Y') . '/' . date('m')) ?>"
       class="btn btn-primary btn-sm text-decoration-none text-white">
        View Full Report <i class="fas fa-chevron-right ms-1"></i>
    </a>
</div>
</div>

<style>
    .attendance-item:last-child {
        border-bottom: none !important;
    }
    .date-circle {
        background: #f8f9fa;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid #e9ecef;
    }
    /* Subtitle colors for Bootstrap 5.3+ */
    .bg-success-subtle { background-color: #d1e7dd; }
    .bg-warning-subtle { background-color: #fff3cd; }
    .bg-danger-subtle { background-color: #f8d7da; }
</style>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .btn-group .btn {
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const locText = document.getElementById('locText');
    const coordText = document.getElementById('coordText');
    const submitBtn = document.getElementById('submitBtn');
    const locSpinner = document.getElementById('locSpinner');

    let map = null;
    let marker = null;
    let hasFetchedAddress = false;
    let lastUpdate = 0;

    function initMap(lat, lng) {
        map = L.map('map').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        marker = L.marker([lat, lng]).addTo(map)
            .bindPopup('Your location')
            .openPopup();
    }

    submitBtn.disabled = true;

    if (!navigator.geolocation) {
        locText.innerText = 'Geolocation not supported';
        locSpinner.classList.add('d-none');
        submitBtn.disabled = false;
        return;
    }

    // fallback jika GPS lama
    setTimeout(() => {
        if (submitBtn.disabled) {
            locText.innerText = 'Using limited location';
            locSpinner.classList.add('d-none');
            submitBtn.disabled = false;
        }
    }, 15000);

    navigator.geolocation.watchPosition(
        pos => {
            const now = Date.now();
            if (now - lastUpdate < 1000) return; // throttle 1 detik
            lastUpdate = now;

            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            // update hidden input
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            // update text
            coordText.innerText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            // init / update map
            if (!map) {
                initMap(lat, lng);
            } else {
                marker.setLatLng([lat, lng]).update();
                map.setView([lat, lng], map.getZoom(), { animate: false });
            }

            // ambil alamat sekali saja
            if (!hasFetchedAddress) {
                hasFetchedAddress = true;

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
                    headers: {
                        'Accept': 'application/json',
                        'User-Agent': 'attendance-app/1.0'
                    }
                })
                .then(r => r.json())
                .then(d => {
                    const address = d.display_name || 'Unknown location';

                    locText.innerText = address;
                    document.getElementById('address').value = address;

                    locSpinner.classList.add('d-none');
                    submitBtn.disabled = false;
                })
                .catch(() => {
                    locText.innerText = 'Address unavailable';
                    locSpinner.classList.add('d-none');
                    submitBtn.disabled = false;
                });
            }
        },
        err => {
            locSpinner.classList.replace('text-primary', 'text-danger');
            locText.classList.add('text-danger');
            locText.innerText = 'Location permission denied';
            coordText.innerText = 'Access denied';
            locSpinner.classList.add('d-none');

            submitBtn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 10000
        }
    );
});
</script>

<?= $this->endSection() ?>