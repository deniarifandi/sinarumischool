<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Dark Mode Base */
    body {
        background-color: #0f172a;
        color: #f1f5f9;
    }

    .main-container {
        width: 100%;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 6px;
    }

    /* Stat cards */
    .stat-card {
        height: 100px;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 6px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .stat-icon-blue   { background: #1e3a8a; color: #60a5fa; }
    .stat-icon-green  { background: #064e3b; color: #4ade80; }
    .stat-icon-red    { background: #7f1d1d; color: #f87171; }
    .stat-icon-orange { background: #7c2d12; color: #fb923c; }

    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #f8fafc;
        line-height: 1.1;
    }
    .stat-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        font-weight: 600;
    }

    .badge-solid {
        display: inline-block;
        padding: 2px 6px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 3px;
        background: #334155;
        color: #cbd5e1;
        border: 1px solid #475569;
    }

    /* Division breakdown list */
    .division-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 14px;
        border-bottom: 1px solid #334155;
        font-size: 13px;
        color: #cbd5e1;
    }
    .division-row:last-child { border-bottom: none; }
    .division-bar-wrap {
        flex: 1;
        margin: 0 12px;
        background: #334155;
        border-radius: 3px;
        height: 6px;
        overflow: hidden;
    }
    .division-bar {
        height: 100%;
        background: #3b82f6;
    }

    /* KKB table */
    .table-solid {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .table-solid thead th {
        background-color: #1e293b;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 10px 12px;
        border-bottom: 2px solid #334155;
        letter-spacing: 0.5px;
    }
    .table-solid tbody td {
        background-color: #1e293b;
        padding: 8px 12px;
        border-bottom: 1px solid #334155;
        vertical-align: middle;
        font-size: 13px;
        color: #e2e8f0;
    }
    .table-solid tbody tr:hover td {
        background-color: #334155 !important;
    }

    .badge-status {
        display: inline-block;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-expired { background: #7f1d1d; color: #fecaca; border: 1px solid #f87171; }
    .badge-urgent  { background: #7c2d12; color: #ffedd5; border: 1px solid #fb923c; }
    .badge-warning { background: #713f12; color: #fef9c3; border: 1px solid #facc15; }
    .badge-ok      { background: #06e177; color: #ffffff; border: 1px solid #06e177; }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #cbd5e1;
        padding: 10px 14px;
        border-bottom: 1px solid #334155;
        background: #1e293b;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-bar select {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        background-color: #334155;
        color: #f1f5f9;
        border: 1px solid #475569;
    }

    .text-muted {
        color: #94a3b8 !important;
    }

    .btn-print {
        font-size: 11px;
        padding: 4px 10px;
        border: 1px solid #475569;
        border-radius: 4px;
        background: #334155;
        color: #f1f5f9;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .btn-print:hover {
        background: #475569;
        color: #fff;
    }

    .print-title { display: none; }

    /* ===================================================================
       PRINT MODE
       Only the KKB Renewal Watchlist table (#kkbPrintSection) is printed.
       Everything else on the page is hidden while printing.
    =================================================================== */
    @media print {
        body * {
            visibility: hidden;
        }

        #kkbPrintSection,
        #kkbPrintSection * {
            visibility: visible;
        }

        #kkbPrintSection {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        body {
            background: #fff !important;
            color: #000 !important;
        }

        .main-container {
            border: 1px solid #999 !important;
            box-shadow: none !important;
            background: #fff !important;
            color: #000 !important;
        }

        table, th, td {
            color: #000 !important;
            border-color: #999 !important;
        }

        .badge-status,
        .badge-solid {
            color: #000 !important;
            border: 1px solid #666 !important;
            background: transparent !important;
        }

        a {
            text-decoration: none;
            color: #000 !important;
        }

        .print-title {
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }

        @page {
            size: A4 landscape;
            margin: 12mm;
        }
    }
</style>

<!-- Stat cards -->
<div class="row g-2 mb-3 no-print">
    <!-- Total Staff Card -->
    <div class="col-md-3">
        <div class="stat-card p-2.5 border rounded shadow-sm position-relative">
            <div class="d-flex align-items-center">
                <div class="stat-icon stat-icon-blue me-2 fs-4 text-primary"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-value fw-bold lh-1 fs-5"><?= esc($totalStaff) ?></div>
                    <div class="stat-label text-muted small" style="font-size: 0.75rem;">Total Staff</div>
                </div>
            </div>
            <a href="<?= base_url('users') ?>" class="position-absolute top-50 translate-middle-y end-0 me-3 btn btn-link p-0 text-decoration-none text-primary small" style="font-size: 0.8rem;">
                Kelola <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- Divisions Card -->
    <div class="col-md-3">
        <div class="stat-card p-2.5 border rounded shadow-sm position-relative">
            <div class="d-flex align-items-center">
                <div class="stat-icon stat-icon-green me-2 fs-4 text-success"><i class="bi bi-diagram-3-fill"></i></div>
                <div>
                    <div class="stat-value fw-bold lh-1 fs-5"><?= esc($totalDivisions) ?></div>
                    <div class="stat-label text-muted small" style="font-size: 0.75rem;">Divisions</div>
                </div>
            </div>
            <a href="<?= base_url('division') ?>" class="position-absolute top-50 translate-middle-y end-0 me-3 btn btn-link p-0 text-decoration-none text-success small" style="font-size: 0.8rem;">
                Kelola <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>

    <!-- KKB Expired Card -->
    <div class="col-md-3">
        <div class="stat-card p-2.5 border rounded shadow-sm position-relative">
            <div class="d-flex align-items-center">
                <div class="stat-icon stat-icon-red me-2 fs-4 text-danger"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <div class="stat-value fw-bold lh-1 fs-5">
                        <?= count(array_filter($kkbNeedsRenewal, fn($k) => isset($k['status']) && $k['status'] === 'expired')) ?>
                    </div>
                    <div class="stat-label text-muted small" style="font-size: 0.75rem;">KKB Expired</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KKB Needs Attention Card -->
    <div class="col-md-3">
        <div class="stat-card p-2.5 border rounded shadow-sm position-relative">
            <div class="d-flex align-items-center">
                <div class="stat-icon stat-icon-orange me-2 fs-4 text-warning"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="stat-value fw-bold lh-1 fs-5"><?= count($kkbNeedsRenewal) ?></div>
                    <div class="stat-label text-muted small" style="font-size: 0.75rem;">Needs Attention</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3 no-print">
    <!-- Staff by KKB duration (Bar chart) -->
    <div class="col-md-6">
        <div class="main-container shadow-sm h-100">
            <div class="section-title"><i class="bi bi-bar-chart-fill me-1"></i> Staff by KKB Duration</div>
            <div class="p-3">
                <div class="w-100" style="height: 500px;">
                    <canvas id="kkbDurationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Division breakdown -->
    <div class="col-md-6">
        <div class="main-container shadow-sm h-100">
            <div class="section-title"><i class="bi bi-diagram-3 me-1"></i> Staff by Division</div>
            <div class="py-2">
                <?php
                    // Dynamic calculation logic captures the single largest pool from the pre-sorted system
                    $maxCount = max(array_merge([1], array_column($divisionCounts, 'count'), [$unassignedDivisionCount]));
                ?>
                <?php foreach ($divisionCounts as $d): ?>
                    <div class="division-row d-flex align-items-center justify-content-between">
                        <div class="text-truncate px-2" style="width: 150px; text-align: left;" title="<?= esc($d['name']) ?>">
                            <?= esc($d['name']) ?>
                        </div>
                        <div class="division-bar-wrap flex-grow-1 mx-3">
                            <div class="division-bar" style="width: <?= $maxCount ? round(($d['count'] / $maxCount) * 100) : 0 ?>%;"></div>
                        </div>
                        <div class="fw-bold px-2 text-end" style="width: 40px;">
                            <?= esc($d['count']) ?>
                        </div>
                    </div>
                <?php endforeach ?>

                <?php if ($unassignedDivisionCount > 0): ?>
                    <div class="division-row d-flex align-items-center justify-content-between">
                        <div class="text-truncate px-2 text-muted" style="width: 150px; text-align: left;">
                            <em>Unassigned</em>
                        </div>
                        <div class="division-bar-wrap flex-grow-1 mx-3">
                            <div class="division-bar" style="width: <?= $maxCount ? round(($unassignedDivisionCount / $maxCount) * 100) : 0 ?>%; background: #475569;"></div>
                        </div>
                        <div class="fw-bold px-2 text-end text-muted" style="width: 40px;">
                            <?= esc($unassignedDivisionCount) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- KKB Renewal Table (this is the only section that prints) -->
<div class="main-container shadow-sm" id="kkbPrintSection">
    <h2 class="print-title">Staff KKB Dashboard Report</h2>

    <div class="section-title">
        <span><i class="bi bi-file-earmark-text me-1"></i> KKB Renewal Watchlist</span>

        <div class="d-flex align-items-center gap-2">
            <form method="get" class="filter-bar d-flex align-items-center gap-2 no-print">
                <label class="text-muted fw-normal mb-0" style="font-size: 11px;">Division:</label>
                <select name="division" onchange="this.form.submit()">
                    <option value="">All Divisions</option>
                    <?php foreach ($allDivisionsForFilter as $d): ?>
                        <option value="<?= esc($d['id']) ?>" <?= $selectedDivision == $d['id'] ? 'selected' : '' ?>>
                            <?= esc($d['division_name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <?php if ($selectedDivision !== null): ?>
                    <a href="<?= current_url() ?>" class="btn-flat" style="font-size: 11px; padding: 3px 8px; border: 1px solid #475569; border-radius: 4px; color: #94a3b8; text-decoration: none;">
                        Clear
                    </a>
                <?php endif; ?>
            </form>

            <button type="button" class="btn-print no-print" onclick="printKkbTable()">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>
    </div>

    <?php if (empty($kkbAll)): ?>
        <div class="p-4 text-center text-muted">
            <i class="bi bi-check-circle fs-3 d-block mb-2 text-success"></i>
            No KKB contracts need attention<?= $selectedDivision !== null ? ' in this division' : '' ?>.
        </div>
    <?php else: ?>
    <div class="table-responsive p-3">
        <table class="table-solid" id="kkbTable">
            <thead>
                <tr>
                    <th>Staff</th>
                    <th>Division</th>
                    <th>KKB No.</th>
                    <th>Duration</th>
                    <th>Start</th>
                    <th>Expiry</th>
                    <th>Days Left</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kkbAll as $k): ?>
                <tr>
                    <td>
                        <div class="fw-bold text-white"><?= esc($k['name']) ?></div>
                        <small class="text-muted">@<?= esc($k['username']) ?></small>
                    </td>
                    <td>
                        <?php if (!empty($k['divisions'])): ?>
                            <?php foreach ($k['divisions'] as $dn): ?>
                                <span class="badge-solid"><?= esc($dn) ?></span>
                            <?php endforeach ?>
                        <?php else: ?>
                            <span class="text-muted small"><em>Unassigned</em></span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($k['kkbnomor'] ?? '—') ?></td>
                    <td><?= esc($k['kkb_years']) ?> tahun</td>
                    <td><?= esc($k['kkbstart']) ?></td>
                    <td><?= esc($k['expiry']) ?></td>
                    <td data-order="<?= $k['days_left'] ?>">
                        <?= $k['days_left'] < 0
                            ? abs($k['days_left']) . ' days ago'
                            : $k['days_left'] . ' days' ?>
                    </td>
                    <td>
                        <?php
                            $badgeClass = [
                                'expired' => 'badge-expired',
                                'urgent'  => 'badge-urgent',
                                'warning' => 'badge-warning',
                                'ok'      => 'badge-ok'
                            ][$k['status']] ?? '';
                        ?>
                        <span class="badge-status <?= $badgeClass ?>"><?= esc($k['status']) ?></span>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(function () {
    $('#kkbTable').DataTable({
        pageLength: 10,
        order: [],
        language: {
            paginate: { previous: '‹', next: '›' }
        }
    });
});

// Expand the table to show every row before the browser print dialog opens,
// then restore normal pagination afterwards. Works whether print is triggered
// via the on-page button or Ctrl/Cmd+P.
function printKkbTable() {
    window.print();
}

window.onbeforeprint = function () {
    $('#kkbTable').DataTable().page.len(-1).draw();
};

window.onafterprint = function () {
    $('#kkbTable').DataTable().page.len(10).draw();
};

// Sorted labels and values passed straight from the PHP controller sorting step
const kkbDurationLabels = <?= json_encode(array_keys($kkbDurationCounts)) ?>;
const kkbDurationData   = <?= json_encode(array_values($kkbDurationCounts)) ?>;
const kkbDurationColors = ['#3b82f6', '#10b981', '#f97316', '#8b5cf6', '#ec4899', '#64748b'];

new Chart(document.getElementById('kkbDurationChart'), {
    type: 'bar',
    data: {
        labels: kkbDurationLabels,
        datasets: [{
            data: kkbDurationData,
            backgroundColor: kkbDurationLabels.map((label, i) =>
                label === 'No KKB Data' ? '#475569' : kkbDurationColors[i % kkbDurationColors.length]
            ),
            borderWidth: 0,
            borderRadius: 4,
            barThickness: 24
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                bodyColor: '#f8fafc',
                titleColor: '#f8fafc',
                backgroundColor: '#0f172a',
                callbacks: {
                    label: (ctx) => ' ' + ctx.parsed.y + ' staff'
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    color: '#cbd5e1',
                    font: { size: 11 }
                }
            },
            y: {
                grid: {
                    color: '#334155',
                    drawBorder: false
                },
                ticks: {
                    color: '#94a3b8',
                    stepSize: 1
                }
            }
        }
    }
});
</script>
<?= $this->endSection() ?>