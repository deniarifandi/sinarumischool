<?= $this->extend('main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- 1. ROW KPI CARD -->
    <div class="row g-3 mb-4">
        <!-- Total Students -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div class="pe-5">
                        <h3 class="fw-bold mb-0"><?= number_format($totalStudents) ?></h3>
                        <span class="text-muted small">Total Students</span>
                    </div>
                    <a href="<?= base_url('student?division='.$divisionId) ?>"
                       class="position-absolute top-50 end-0 translate-middle-y me-3 text-decoration-none text-primary fw-semibold small stretched-link">
                        View <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Male Students -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-success bg-opacity-10 text-success rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-gender-male fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($maleStudents) ?></h3>
                        <span class="text-muted small">Male</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Female Students -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-gender-female fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($femaleStudents) ?></h3>
                        <span class="text-muted small">Female</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Classes & Grades -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-warning bg-opacity-10 text-warning rounded p-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-building fs-4"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0"><?= number_format($totalClasses) ?></h3>
                        <span class="text-muted small">Classes (<?= $totalGrades ?> Grades)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. ROW CHARTS UTAMA -->
    <div class="row g-3 mb-4">
        <!-- Chart Demografi Tingkat Kelas -->
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">Students by Grade</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartGrades" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Chart Gender (Donut) -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">Gender Distribution</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartGender" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. ROW DATA SEKUNDER -->
    <div class="row g-3">
        <!-- Tabel Detail Murid Per Kelas -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-2">
                    <h5 class="fw-bold mb-0">Class Breakdown</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3">Grade</th>
                                    <th>Class Name</th>
                                    <th class="text-end pe-3">Total Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($studentsPerClass)): ?>
                                    <?php foreach ($studentsPerClass as $row): ?>
                                    <tr>
                                        <td class="ps-3"><span class="badge bg-secondary"><?= esc($row['grade']) ?></span></td>
                                        <td><?= esc($row['class_name']) ?></td>
                                        <td class="text-end pe-3 fw-semibold"><?= number_format($row['total']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">No data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Golongan Darah & Agama (Dua Card Pendek Stacked) -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-0">
                    <h5 class="fw-bold mb-0">Blood Types</h5>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartBlood" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-transparent border-0 pt-3 pb-2">
                    <h5 class="fw-bold mb-0">Religions</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="list-group list-group-flush">
                        <?php if(!empty($religions)): ?>
                            <?php foreach($religions as $rel): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="small text-secondary"><?= esc($rel['murid_agama'] ?: 'Unknown') ?></span>
                                <span class="badge bg-light text-dark border fw-semibold"><?= number_format($rel['total']) ?></span>
                            </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-center text-muted px-0">No data</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<!-- 4. SECTION SCRIPT UNTUK CHART JS -->
<?= $this->section('script') ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // Data PHP to JSON mapping
    const gradeLabels = <?= json_encode(array_column($grades, 'grade')) ?>;
    const gradeData = <?= json_encode(array_column($grades, 'total')) ?>;
    
    const bloodLabels = <?= json_encode(array_column($bloodTypes, 'blood_type')) ?>.map(x => x || 'Unknown');
    const bloodData = <?= json_encode(array_column($bloodTypes, 'total')) ?>;

    // 1. Chart Grades (Bar Chart)
    new Chart(document.getElementById('chartGrades'), {
        type: 'bar',
        data: {
            labels: gradeLabels,
            datasets: [{
                label: 'Students',
                data: gradeData,
                backgroundColor: 'rgba(13, 110, 253, 0.85)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true, grid: { display: false } } }
        }
    });

    // 2. Chart Gender (Doughnut)
    new Chart(document.getElementById('chartGender'), {
        type: 'doughnut',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [<?= $maleStudents ?>, <?= $femaleStudents ?>],
                backgroundColor: ['#198754', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 3. Chart Blood Type (Polar Area / Pie)
    new Chart(document.getElementById('chartBlood'), {
        type: 'pie',
        data: {
            labels: bloodLabels,
            datasets: [{
                data: bloodData,
                backgroundColor: ['#0dcaf0', '#ffc107', '#fd7e14', '#6f42c1', '#6c757d']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

});
</script>
<?= $this->endSection() ?>