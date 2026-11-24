<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4><i class="bi bi-calendar3 me-2"></i>Academic Calendar</h4>
            <div class="text-muted"><?= esc($division['division_name']) ?></div>
        </div>
        <a href="<?= base_url() ?>" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?php if ($academicYears): ?>
            <form method="get" action="<?= base_url('academic-calendar') ?>" class="row g-3 align-items-end">
                <input type="hidden" name="division" value="<?= esc($division['id']) ?>">
                <div class="col-md-8">
                    <label for="calendarAcademicYear" class="form-label">Academic Year</label>
                    <select id="calendarAcademicYear" name="academic_year_id" class="form-select">
                        <?php foreach ($academicYears as $year): ?>
                        <option value="<?= esc($year['id']) ?>" <?= (int) $year['id'] === (int) $selectedYear['id'] ? 'selected' : '' ?>>
                            <?= esc($year['name']) ?><?= !empty($year['is_active']) ? ' (Active)' : '' ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4"><button class="btn btn-primary" type="submit">Show Calendar</button></div>
            </form>
            <?php else: ?>
            <p class="text-muted mb-0">Belum ada tahun ajaran untuk divisi ini.</p>
            <?php endif; ?>
            <a href="<?= base_url('academic-year?division=' . (int) $division['id']) ?>" class="btn btn-outline-primary btn-sm mt-3">Manage Academic Year</a>
        </div>
    </div>
    <?php if ($calendar): ?>
    <?php
        $monthUrl = static fn ($month) => base_url('academic-calendar') . '?' . http_build_query([
            'division' => $division['id'], 'academic_year_id' => $selectedYear['id'], 'month' => $month,
        ]);
    ?>
    <style>
        .academic-calendar-scroll { overflow-x: auto; }
        .academic-calendar { width: 100%; min-width: 700px; table-layout: fixed; border-collapse: collapse; }
        .academic-calendar th { padding: .75rem; text-align: center; }
        .academic-calendar td { height: 120px; vertical-align: top; padding: .5rem; border: 1px solid var(--bs-border-color, #dee2e6); }
        .academic-calendar .calendar-muted { background: rgba(128,128,128,.08); color: var(--bs-secondary-color, #6c757d); }
        .academic-calendar .calendar-weekend .calendar-day-number { color: #dc3545; }
        .academic-calendar .calendar-today { box-shadow: inset 0 0 0 2px var(--bs-primary, #0d6efd); }
        .calendar-event { font-size: .72rem; margin-top: .35rem; padding: .25rem .4rem; border-radius: .25rem; overflow-wrap: anywhere; }
    </style>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <h5 class="mb-0"><?= esc($calendar['label']) ?></h5>
                <div class="d-flex align-items-center gap-2">
                    <?php if ($calendar['previous']): ?>
                    <a href="<?= esc($monthUrl($calendar['previous']), 'attr') ?>" class="btn btn-outline-primary btn-sm" aria-label="Previous month"><i class="bi bi-chevron-left"></i></a>
                    <?php endif; ?>
                    <form method="get" action="<?= base_url('academic-calendar') ?>" class="d-flex gap-2">
                        <input type="hidden" name="division" value="<?= esc($division['id']) ?>">
                        <input type="hidden" name="academic_year_id" value="<?= esc($selectedYear['id']) ?>">
                        <select name="month" class="form-select form-select-sm" aria-label="Calendar month">
                            <?php foreach ($calendar['months'] as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= $value === $calendar['month'] ? 'selected' : '' ?>><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Show</button>
                    </form>
                    <?php if ($calendar['next']): ?>
                    <a href="<?= esc($monthUrl($calendar['next']), 'attr') ?>" class="btn btn-outline-primary btn-sm" aria-label="Next month"><i class="bi bi-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="academic-calendar-scroll">
                <table class="academic-calendar" aria-label="<?= esc($calendar['label'], 'attr') ?>">
                    <thead><tr><?php foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $weekday): ?><th scope="col"><?= $weekday ?></th><?php endforeach; ?></tr></thead>
                    <tbody>
                    <?php foreach (array_chunk($calendar['days'], 7) as $week): ?>
                        <tr>
                        <?php foreach ($week as $day): ?>
                            <td class="<?= !$day['inMonth'] || !$day['inYear'] ? 'calendar-muted ' : '' ?><?= $day['weekend'] ? 'calendar-weekend ' : '' ?><?= $day['today'] ? 'calendar-today' : '' ?>" <?= $day['today'] ? 'aria-current="date"' : '' ?>>
                                <time class="calendar-day-number fw-semibold" datetime="<?= esc($day['date']) ?>"><?= esc($day['number']) ?></time>
                                <?php foreach ($day['events'] as $event): ?>
                                <div class="calendar-event <?= isset($event['color']) ? '' : 'bg-' . esc($event['type']) . ' text-white' ?>" <?php if (isset($event['color'])): ?>style="background-color:<?= esc($event['color'], 'attr') ?>;color:<?= esc($event['textColor'], 'attr') ?>"<?php endif; ?>><?= esc($event['label']) ?></div>
                                <?php endforeach; ?>
                            </td>
                        <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-wrap gap-3 small mt-3">
                <span><span class="badge bg-primary">Starts</span> Awal periode</span>
                <span><span class="badge bg-secondary">Ends</span> Akhir periode</span>
                <span><span class="badge bg-success">Report Card</span> Pembagian rapor</span>
                <span class="text-muted">Tanggal hari ini ditandai bingkai biru.</span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($selectedYear): ?>
    <?= $this->include('academic_calendar/_events') ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5><?= esc($selectedYear['name']) ?></h5>
            <p class="text-muted"><?= esc($selectedYear['start_date']) ?> &ndash; <?= esc($selectedYear['end_date']) ?></p>
            <?php if ($terms): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Semester</th><th>Term</th><th>Start Date</th><th>End Date</th><th>Report Date</th></tr></thead>
                    <tbody>
                        <?php foreach ($terms as $term): ?>
                        <tr>
                            <td><?= esc($term['semester_name']) ?></td>
                            <td><?= esc($term['name']) ?></td>
                            <td><?= esc($term['start_date']) ?></td>
                            <td><?= esc($term['end_date']) ?></td>
                            <td><?= esc($term['report_date'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p class="text-muted">Belum ada term untuk tahun ajaran ini.</p>
            <?php endif; ?>
            <p class="text-muted small mb-0">Jadwal kalender mengikuti data Academic Year dan Term.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


<?= $this->section('modal') ?>
<?php if ($selectedYear && $calendar): ?>
<?= view('academic_calendar/_event_form', ['event'=>[]]) ?>
<?php foreach ($events as $event): ?>
<?= view('academic_calendar/_event_form', ['event'=>$event]) ?>
<?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const eventMonthFilter = document.getElementById('manageEventMonth');
    if (eventMonthFilter) {
        function filterManagedEvents() {
            const month = eventMonthFilter.value;
            let visibleCount = 0;
            document.querySelectorAll('#managedEventList .managed-event').forEach(function (row) {
                const visible = !month || (row.dataset.start.slice(0, 7) <= month && row.dataset.end.slice(0, 7) >= month);
                row.hidden = !visible;
                if (visible) visibleCount++;
            });
            document.getElementById('managedEventCount').textContent = visibleCount;
            const empty = document.getElementById('managedEventEmpty');
            if (empty) empty.hidden = visibleCount > 0;
        }
        eventMonthFilter.addEventListener('change', filterManagedEvents);
        filterManagedEvents();
    }

    document.querySelectorAll('.event-color-picker').forEach(function (picker) {
        const input = picker.querySelector('input[name="color"]');
        const swatches = picker.querySelectorAll('.event-color-swatch');
        function refreshSelection() {
            swatches.forEach(function (swatch) {
                const selected = swatch.dataset.color.toLowerCase() === input.value.toLowerCase();
                swatch.setAttribute('aria-pressed', String(selected));
                swatch.style.outline = selected ? '2px solid var(--bs-primary, #0d6efd)' : '';
                swatch.style.outlineOffset = selected ? '3px' : '';
            });
        }
        swatches.forEach(function (swatch) {
            swatch.addEventListener('click', function () {
                input.value = swatch.dataset.color;
                input.dispatchEvent(new Event('input', { bubbles: true }));
            });
        });
        input.addEventListener('input', refreshSelection);
        refreshSelection();
    });

    document.querySelectorAll('[id^="eventModal-"]').forEach(function (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            modal.querySelector('[name="title"]').focus();
        });
    });
    const retryId = <?= json_encode((string) session()->getFlashdata('calendar_edit_id'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    const retryModal = document.getElementById('eventModal-' + retryId);
    if (retryId && retryModal) bootstrap.Modal.getOrCreateInstance(retryModal).show();
});
</script>
<?= $this->endSection() ?>
