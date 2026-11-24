<?php
$key = isset($event['id']) ? (string) (int) $event['id'] : 'new';
$isRetry = (string) session()->getFlashdata('calendar_edit_id') === $key;
$value = static fn ($field, $default = '') => $isRetry ? old($field, $default, false) : ($event[$field] ?? $default);
$prefix = 'event-' . $key . '-';
?>
<div class="modal fade" id="eventModal-<?= esc($key) ?>" tabindex="-1" aria-labelledby="<?= esc($prefix) ?>heading" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form method="post" action="<?= base_url('academic-calendar/events' . (isset($event['id']) ? '/' . (int) $event['id'] : '')) ?>" class="d-flex flex-column overflow-hidden">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="<?= esc($prefix) ?>heading"><?= $key === 'new' ? 'Tambah Event' : 'Edit Event' ?></h5>
                        <div class="small text-muted mt-1"><?= esc($division['division_name']) ?> &middot; <?= esc($selectedYear['name']) ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body p-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="division" value="<?= esc($division['id']) ?>">
                    <input type="hidden" name="academic_year_id" value="<?= esc($selectedYear['id']) ?>">
                    <input type="hidden" name="month" value="<?= esc($calendar['month']) ?>">
                    <?php if ($isRetry && ($errors = session()->getFlashdata('calendar_errors'))): ?>
                    <div class="alert alert-danger" role="alert"><ul class="mb-0"><?php foreach ((array) $errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="<?= esc($prefix) ?>title" class="form-label fw-semibold">Nama Event <span class="text-danger">*</span></label>
                            <input id="<?= esc($prefix) ?>title" name="title" class="form-control" required maxlength="150" placeholder="Contoh: Ujian Tengah Semester" value="<?= esc($value('title'), 'attr') ?>">
                        </div>
                        <div class="col-12">
                            <label for="<?= esc($prefix) ?>category" class="form-label fw-semibold">Jenis Event <span class="text-danger">*</span></label>
                            <select id="<?= esc($prefix) ?>category" name="category" class="form-select" required>
                                <?php foreach ($categories as $categoryKey => $label): ?><option value="<?= esc($categoryKey) ?>" <?= $value('category', 'activity') === $categoryKey ? 'selected' : '' ?>><?= esc($label) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="<?= esc($prefix) ?>color" class="form-label fw-semibold">Warna Event</label>
                            <div class="event-color-picker d-flex flex-wrap align-items-center gap-3">
                                <div class="d-flex flex-wrap gap-2" role="group" aria-label="Pilihan warna event">
                                    <?php foreach (['#0dcaf0'=>'Biru muda', '#0d6efd'=>'Biru', '#198754'=>'Hijau', '#ffc107'=>'Kuning', '#fd7e14'=>'Oranye', '#dc3545'=>'Merah', '#a855f7'=>'Ungu', '#ec4899'=>'Merah muda', '#212529'=>'Hitam', '#6c757d'=>'Abu-abu'] as $hex => $colorLabel): ?>
                                    <button type="button" class="event-color-swatch rounded" style="background-color:<?= $hex ?>;width:30px;height:30px;border:2px solid rgba(128,128,128,.4)" data-color="<?= $hex ?>" title="<?= $colorLabel ?>" aria-label="<?= $colorLabel ?>" aria-pressed="<?= strtolower($value('color', \App\Libraries\AcademicCalendar::eventColor($event))) === $hex ? 'true' : 'false' ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <input id="<?= esc($prefix) ?>color" type="color" name="color" class="form-control form-control-color" required value="<?= esc($value('color', \App\Libraries\AcademicCalendar::eventColor($event)), 'attr') ?>" title="Pilih warna lainnya">
                                    <span class="small text-muted">Warna lainnya</span>
                                </div>
                            </div>
                        </div>
                        <?php foreach (['start_date'=>'Tanggal Mulai', 'end_date'=>'Tanggal Selesai'] as $field => $label): ?>
                        <div class="col-sm-6">
                            <label for="<?= esc($prefix . $field) ?>" class="form-label fw-semibold"><?= $label ?> <span class="text-danger">*</span></label>
                            <input id="<?= esc($prefix . $field) ?>" type="date" name="<?= $field ?>" class="form-control" required min="<?= esc($selectedYear['start_date']) ?>" max="<?= esc($selectedYear['end_date']) ?>" value="<?= esc($value($field), 'attr') ?>">
                        </div>
                        <?php endforeach; ?>
                        <div class="col-12"><div class="form-text mt-0">Rentang tahun ajaran: <?= esc($selectedYear['start_date']) ?> &ndash; <?= esc($selectedYear['end_date']) ?>.</div></div>
                        <div class="col-12">
                            <label for="<?= esc($prefix) ?>description" class="form-label fw-semibold">Keterangan <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea id="<?= esc($prefix) ?>description" name="description" class="form-control" maxlength="5000" rows="3" placeholder="Tambahkan informasi kegiatan..."><?= esc($value('description')) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check-lg me-1"></i>Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
