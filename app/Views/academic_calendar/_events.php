<?php if ($message = session()->getFlashdata('calendar_success')): ?>
<div class="alert alert-success" role="status"><?= esc($message) ?></div>
<?php endif; ?>
<?php if ($errors = session()->getFlashdata('calendar_errors')): ?>
<div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ((array) $errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div><h5 class="mb-1">Manage Events</h5><div class="small text-muted"><?= esc($selectedYear['name']) ?> &middot; <span id="managedEventCount"><?= count($events) ?></span> event</div></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal-new">
                <i class="bi bi-plus-lg me-1"></i>Tambah Event
            </button>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6 col-lg-4">
                <label for="manageEventMonth" class="form-label fw-semibold">Filter Bulan</label>
                <select id="manageEventMonth" class="form-select" aria-controls="managedEventList">
                    <option value="">Semua Bulan</option>
                    <?php foreach ($calendar['months'] as $monthValue => $monthLabel): ?>
                    <option value="<?= esc($monthValue) ?>"><?= esc($monthLabel) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div id="managedEventList">
        <?php if (!$events): ?><p class="text-muted mb-0">Belum ada event. Tambahkan kegiatan pertama untuk tahun ajaran ini.</p><?php endif; ?>
        <?php foreach ($events as $event): ?>
        <div class="managed-event border rounded p-3 mb-3" data-start="<?= esc($event['start_date'], 'attr') ?>" data-end="<?= esc($event['end_date'], 'attr') ?>">
            <div class="d-flex flex-wrap justify-content-between gap-2">
                <div>
                    <h6 class="mb-1"><span class="d-inline-block rounded me-1" style="width:12px;height:12px;background-color:<?= esc(\App\Libraries\AcademicCalendar::eventColor($event), 'attr') ?>" aria-hidden="true"></span><?= esc($event['title']) ?> <span class="badge bg-secondary"><?= esc($categories[$event['category']] ?? $event['category']) ?></span></h6>
                    <div class="small text-muted"><?= esc($event['start_date']) ?> &ndash; <?= esc($event['end_date']) ?></div>
                    <?php if ($event['description']): ?><div class="small mt-2" style="white-space:pre-wrap"><?= esc($event['description']) ?></div><?php endif; ?>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#eventModal-<?= (int) $event['id'] ?>"><i class="bi bi-pencil me-1"></i>Edit</button>
                <form method="post" action="<?= base_url('academic-calendar/events/' . (int) $event['id'] . '/delete') ?>" onsubmit="return confirm('Hapus event ini?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="division" value="<?= esc($division['id']) ?>">
                    <input type="hidden" name="academic_year_id" value="<?= esc($selectedYear['id']) ?>">
                    <input type="hidden" name="month" value="<?= esc($calendar['month']) ?>">
                    <button class="btn btn-outline-danger btn-sm" type="submit">Hapus</button>
                </form>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
        </div>
        <?php if ($events): ?>
        <p id="managedEventEmpty" class="text-muted" role="status" hidden>Tidak ada event pada bulan yang dipilih.</p>
        <?php endif; ?>
        <p class="small text-muted mb-0">Event tidak otomatis mengubah absensi atau mengunci nilai. Jadwal term dikelola melalui Academic Year.</p>
    </div>
</div>
