<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<style>
    /* Screen Styles (Dark Glassmorphism Theme) */
    .glass-card {
      
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }
    
    /* Crisp White Table Styles */
    .table-white-custom {
        background-color: #ffffff !important;
        color: #212529 !important;
        border-radius: 8px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    .table-white-custom th {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 12px;
        border-bottom: 2px solid #dee2e6 !important;
    }
    .table-white-custom td {
        background-color: #ffffff !important;
        color: #212529 !important;
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef !important;
    }
    .table-white-custom tfoot tr {
        background-color: #f8f9fa !important;
    }

    /* PRINT ENGINE: Controls print-specific behavior */
    @media print {
        /* 1. Hide interactive elements completely from paper layout */
        .no-print, 
        form, 
        .btn, 
        a[href] {
            display: none !important;
        }

        /* 2. Strip dark/transparent layouts out to preserve text clarity */
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .glass-card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* 3. Force clean print headers */
        .text-white {
            color: #000000 !important;
        }
        .text-white-50 {
            color: #495057 !important;
        }

        /* 4. Optimize the data grid for physical printing layouts */
        .table-white-custom {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
            width: 100% !important;
        }
        .table-white-custom th, 
        .table-white-custom td {
            border: 1px solid #dee2e6 !important;
            color: #000000 !important;
            padding: 8px !important;
        }
    }
</style>
<style>
@media print {

    body * {
        visibility: hidden;
    }

    #print-area,
    #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    .btn,
    form,
    nav,
    aside,
    header,
    footer {
        display: none !important;
    }
}
</style>
<div class="" id="print-area">
    <div class="glass-card mx-auto">

        <!-- Header Block -->
        <div class="d-flex justify-content-between align-items-center pb-3 mb-4 border-bottom border-secondary-subtle">
            <div>
                <h4 class="mb-1 text-white">Monthly Attendance Recap</h4>
                <p class="text-white-50 small mb-0">
                    Class Structure: <?= esc($class_name) ?>
                </p>
            </div>

            <!-- Intermittent Back Control Element -->
            <a href="<?= base_url('student/attendance/list/class/'.$class_id) ?>"
               class="btn btn-sm btn-outline-light no-print">
                ← Back to List
            </a>
        </div>

        <!-- Filter controls section -->
        <form method="post" action="<?= base_url('student/attendance/recap') ?>" class="row g-3 mb-4 no-print">
            <?= csrf_field() ?>
            <input type="hidden" name="class_id" value="<?= $class_id ?>">

            <div class="col-md-4">
                <label class="form-label small text-white-50">Month</label>
                <select name="month" class="form-select bg-dark text-white border-secondary">
                    <?php
                    $months = [
                        1=>'January', 2=>'February', 3=>'March', 4=>'April',
                        5=>'May', 6=>'June', 7=>'July', 8=>'August',
                        9=>'September', 10=>'October', 11=>'November', 12=>'December'
                    ];
                    ?>
                    <?php foreach($months as $key => $value): ?>
                        <option value="<?= $key ?>" <?= ($month == $key) ? 'selected' : '' ?>>
                            <?= $value ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label small text-white-50">Year</label>
                <select name="year" class="form-select bg-dark text-white border-secondary">
                    <?php for($y = date('Y')-2; $y <= date('Y')+2; $y++): ?>
                        <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-medium">
                    Show Grid
                </button>
            </div>

            <?php if(!empty($data)): ?>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" onclick="window.print()" class="btn btn-success w-100 py-2 fw-medium">
                    Print Report
                </button>
            </div>
            <?php endif; ?>
        </form>

        <!-- Structured Data Report Grid Output -->
        <?php if(!empty($data)): ?>

            <!-- Clear Meta context for Printed Reports -->
            <div class="mb-4 p-3 rounded text-white print-meta-box" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); shadow: none;">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <span class="text-white-50 small d-block">Target Class Group</span>
                        <strong class="text-white font-monospace"><?= esc($class_name) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-white-50 small d-block">Log Summary Period</span>
                        <strong class="text-info"><?= $months[$month] ?> <?= $year ?></strong>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-white-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 8%">No</th>
                            <th style="width: 32%">Student Name</th>
                            <th style="width: 15%" class="text-center text-success">Present</th>
                            <th style="width: 15%" class="text-center text-info">Permission</th>
                            <th style="width: 15%" class="text-center text-warning">Sick</th>
                            <th style="width: 15%" class="text-center text-danger">Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php
                        $totalPresent = 0;
                        $totalPermission = 0;
                        $totalSick = 0;
                        $totalAbsent = 0;
                        ?>
                        <?php foreach($data as $row): ?>
                            <?php
                            $totalPresent    += $row['hadir'];
                            $totalPermission += $row['izin'];
                            $totalSick       += $row['sakit'];
                            $totalAbsent     += $row['alpha'];
                            ?>
                            <tr>
                                <td class="text-muted small fw-medium"><?= $no++ ?></td>
                                <td class="fw-semibold text-dark"><?= esc($row['name']) ?></td>
                                <td class="text-center fw-medium"><?= $row['hadir'] ?></td>
                                <td class="text-center fw-medium"><?= $row['izin'] ?></td>
                                <td class="text-center fw-medium"><?= $row['sakit'] ?></td>
                                <td class="text-center fw-medium <?= ($row['alpha'] > 0) ? 'text-danger fw-bold' : '' ?>"><?= $row['alpha'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold text-dark">
                            <td colspan="2" class="text-end px-3 small text-secondary">TOTAL MATRICES:</td>
                            <td class="text-center bg-light text-success"><?= $totalPresent ?></td>
                            <td class="text-center bg-light text-info"><?= $totalPermission ?></td>
                            <td class="text-center bg-light text-warning"><?= $totalSick ?></td>
                            <td class="text-center bg-light text-danger"><?= $totalAbsent ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>