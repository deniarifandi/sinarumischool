<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<style>
    /* Mengubah glass-card menjadi main-card putih bersih */
    .main-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    
    /* Styling khusus kartu statistik */
    .stat-card {
        background: #ffffff;
        border: 1px solid #eaedf1;
        border-radius: 1rem;
        transition: all 0.3s ease-in-out;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        border-color: #3b82f6; /* Warna biru saat hover */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
    
    /* Membuat ujung tabel membulat */
    .table-custom-rounded {
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #eaedf1;
    }
</style>

<div class="container-fluid px-0 py-3">

    <!-- PROFILE HEADER -->
    <div class="main-card p-4 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                 style="width:60px; height:60px;">
                <i class="bi bi-person-fill fs-2"></i>
            </div>
            <div>
                <h4 class="text-dark fw-bold mb-1"><?= esc($student['name']) ?></h4>
                <div class="text-muted small d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border border-secondary-subtle px-2 py-1">
                        <?= esc($student['class_name'] ?? 'No current class') ?>
                    </span>
                    <span>&bull;</span>
                    <span>Student ID: <strong class="text-dark"><?= esc($student['id']) ?></strong></span>
                </div>
            </div>
        </div>

        <a href="<?= base_url('gradebook/directory') ?>" onclick="history.back(); return false;" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
    <i class="bi bi-arrow-left me-2"></i> Back
</a>

        <!-- Filter Subject -->
        <div class="main-card p-3 mb-0 d-flex justify-content-end align-items-center shadow-sm ms-auto w-100" style="max-width: 400px; border-radius: 0.75rem;">
            <form method="get" action="" class="d-flex align-items-center gap-2 w-100">
                <label for="subject_id" class="text-dark mb-0 fw-semibold text-nowrap">Filter Subject:</label>
                <select name="subject_id" id="subject_id" class="form-select form-select-sm border-secondary-subtle" onchange="this.form.submit()">
                    <option value="">-- All Subjects --</option>
                    <?php foreach($availableSubjects as $id => $name): ?>
                        <option value="<?= esc($id) ?>" <?= ($id == $selectedSubjectId) ? 'selected' : '' ?>>
                            <?= esc($name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card h-100 p-3 d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                    <i class="bi bi-journal-check fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0">
                        <?= $overallAverage !== null ? number_format($overallAverage, 1) : '-' ?>
                    </h3>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wide">Overall Average</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card h-100 p-3 d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                    <i class="bi bi-book-half fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($totalSubjects) ?></h3>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wide">Subjects Tracked</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card h-100 p-3 d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                    <i class="bi bi-calendar3 fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($totalTerms) ?></h3>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wide">Terms Recorded</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card h-100 p-3 d-flex align-items-center">
                <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 me-3 d-flex align-items-center justify-content-center" style="width:54px; height:54px;">
                    <i class="bi bi-clipboard2-check fs-4"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0">
                        <?php if ($lifetimeAttendance && $lifetimeAttendance['total_meetings'] > 0): ?>
                            <?= round((1 - ($lifetimeAttendance['unauthorized'] / $lifetimeAttendance['total_meetings'])) * 100) ?>%
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </h3>
                    <span class="text-muted small text-uppercase fw-semibold tracking-wide">Attendance Rate</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CHART -->
    <div class="main-card p-4 mb-4">
        <h6 class="text-dark fw-bold mb-4 d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                <i class="bi bi-graph-up text-primary"></i>
            </div>
            Grade Trend (Average per Term)
        </h6>

        <?php if (empty($chartLabels)): ?>
            <div class="alert alert-light border border-secondary-subtle text-muted text-center py-4 rounded-3">
                <i class="bi bi-bar-chart text-muted fs-2 d-block mb-2 opacity-50"></i>
                No grade history available yet.
            </div>
        <?php else: ?>
            <canvas id="gradeTrendChart" height="80"></canvas>
        <?php endif; ?>
    </div>

    <!-- HISTORY TABLE -->
    <div class="main-card p-4">
        <h6 class="text-dark fw-bold mb-4 d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                <i class="bi bi-clock-history text-primary"></i>
            </div>
            Grade History
        </h6>

        <?php if (empty($byTerm)): ?>
            <div class="empty-state text-center py-5">
                <i class="bi bi-journal-x text-muted opacity-25" style="font-size: 4rem;"></i>
                <div class="fw-semibold text-dark mt-3 fs-5">No grade history found</div>
                <div class="text-muted mt-1">This student has no recorded gradebook entries yet.</div>
            </div>
        <?php else: ?>
            <?php foreach ($byTerm as $term): ?>
                <div class="mb-5">
                    <!-- Term Header -->
                    <div class="d-flex align-items-center mb-3">
                        <h6 class="text-dark fw-bold mb-0 me-3">
                            <i class="bi bi-bookmark-star text-warning me-2"></i>
                            <?= esc($term['term_name']) ?> &mdash; <?= esc($term['semester_name']) ?>
                            <span class="text-muted fw-normal">(<?= esc($term['academic_year_name']) ?>)</span>
                        </h6>
                        <span class="badge bg-light text-dark border border-secondary-subtle px-3 py-2 rounded-pill">
                            <?= esc($term['class_name']) ?>
                        </span>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive table-custom-rounded shadow-sm">
                        <table class="table table-hover align-middle mb-0 bg-white">
                            <thead class="table-light text-muted text-uppercase" style="font-size: 0.85rem;">
                                <tr>
                                    <th class="py-3 px-3 w-25 fw-semibold border-bottom-0">Subject</th>
                                    <th class="py-3 text-center fw-semibold border-bottom-0">CT1</th>
                                    <th class="py-3 text-center fw-semibold border-bottom-0">CT1 Rem.</th>
                                    <th class="py-3 text-center fw-semibold border-bottom-0">CT2</th>
                                    <th class="py-3 text-center fw-semibold border-bottom-0">CT2 Rem.</th>
                                    <th class="py-3 text-center fw-semibold border-bottom-0">Indv. Project</th>
                                    <th class="py-3 text-center fw-semibold border-bottom-0">Group Project</th>
                                    <!-- Header Average (Highlight) -->
                                    <th class="py-3 text-center text-dark fw-bold bg-warning bg-opacity-10 border-bottom-0">Avg</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($term['subjects'] as $row): ?>
                                    <tr>
                                        <td class="px-3 fw-medium text-dark"><?= esc($row['subject_name']) ?></td>
                                        
                                        <?php 
                                            $cols = ['ct1', 'ct1_remedial', 'ct2', 'ct2_remedial', 'individual_project', 'group_project'];
                                            foreach($cols as $col): 
                                                $val = $row[$col] ?? null;
                                        ?>
                                            <td class="text-center">
                                                <?php if ($val !== null && $val !== '' && $val !== '-'): ?>
                                                    <span class="fw-bold text-dark"><?= esc($val) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted opacity-50">-</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>

                                        <!-- Data Average (Highlight) -->
                                        <td class="text-center bg-warning bg-opacity-10">
                                            <?php if (isset($row['subject_average']) && $row['subject_average'] !== null): ?>
                                                <span class="fw-bold text-dark"><?= esc($row['subject_average']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted opacity-50">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php if (!empty($chartLabels)): ?>
<script>
Chart.register(ChartDataLabels);

const ctx = document.getElementById('gradeTrendChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Average Score',
            data: <?= json_encode($chartAverages) ?>,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.15)',
            borderWidth: 3,
            pointBackgroundColor: '#fff',
            pointBorderColor: '#3b82f6',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: true,
            tension: 0.3,
            spanGaps: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { 
                beginAtZero: false, 
                max: 100,
                // Mengubah warna grid dan teks sumbu Y agar cocok untuk latar putih
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: { color: 'rgba(0, 0, 0, 0.5)' },
                grace: '15%'
            },
            x: {
                grid: { display: false },
                // Mengubah warna teks sumbu X
                ticks: { color: 'rgba(0, 0, 0, 0.6)', font: { weight: 'bold' } }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFont: { size: 13 },
                bodyFont: { size: 14, weight: 'bold' },
                padding: 10,
                displayColors: false
            },
            datalabels: {
                align: 'top',
                anchor: 'end',
                color: '#3b82f6', // Mengubah warna label angka di atas titik grafik
                offset: 4,
                font: { weight: 'bold', size: 14 },
                formatter: Math.round
            }
        }
    }
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>      