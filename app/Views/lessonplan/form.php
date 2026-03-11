<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?php 
        $error = session()->getFlashdata('error');
        if (is_array($error)) {
            foreach ($error as $e) {
                echo '<div>' . esc($e) . '</div>';
            }
        } else {
            echo esc($error);
        }
        ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<div class="container-fluid pb-5">
    <form method="post" action="<?= isset($lessonplan) ? base_url('lessonplan/update/'.$lessonplan['id']) : base_url('lessonplan/store') ?>">
        <?= csrf_field() ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0 text-white">
                    <?= isset($lessonplan) ? '<i class="bi bi-pencil-square"></i> Edit Lesson Plan' : '<i class="bi bi-plus-circle"></i> Create Lesson Plan' ?>
                </h4>
                <p class="text-white-50 mb-0">Fill in the details to organize your teaching schedule.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= base_url('lessonplan') ?>" class="btn btn-outline-light rounded-pill px-4">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow">
                    <i class="bi bi-save me-1"></i> <?= isset($lessonplan) ? 'Update Plan' : 'Save Plan' ?>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="glass-card mb-4">
                    <h6 class="text-uppercase fw-bold text-primary mb-3">General Information</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Class ID</label>
                            <input type="text" name="class_id" class="form-control bg-light" value="<?= old('class_id', $mainClass['id'] ?? $lessonplan['class_id']) ?>" readonly>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Nama Kelas</label>
                            <input type="text" class="form-control bg-light" value="<?= old('class_name', $mainClass['class_name'] ?? '') ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Unit / Subunit</label>
                            <div class="input-group">
                                <select name="unit_id" class="form-select">
                                    <option value="">Unit...</option>
                                    <?php foreach ($units as $u): ?>
                                        <option value="<?= esc($u['id']) ?>" <?= old('unit_id', $lessonplan['unit_id'] ?? '') == $u['id'] ? 'selected' : '' ?>><?= esc($u['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="subunit_id" class="form-select">
                                    <option value="">Subunit...</option>
                                    <?php foreach ($subunits as $s): ?>
                                        <option value="<?= esc($s['id']) ?>" <?= old('subunit_id', $lessonplan['subunit_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Semester</label>
                            <select name="semester" class="form-select">
                                <option value="1" <?= old('semester', $lessonplan['semester'] ?? '') == '1' ? 'selected' : '' ?>>1</option>
                                <option value="2" <?= old('semester', $lessonplan['semester'] ?? '') == '2' ? 'selected' : '' ?>>2</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Bulan</label>
                            <select name="bulan" class="form-select">
                                <?php $months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                                $selectedMonth = old('bulan', $lessonplan['bulan'] ?? ''); ?>
                                <?php foreach ($months as $m): ?>
                                    <option value="<?= $m ?>" <?= $selectedMonth === $m ? 'selected' : '' ?>><?= $m ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">DPL (Dosen Pembimbing)</label>
                            <input type="text" name="dpl" class="form-control" placeholder="Enter name..." value="<?= old('dpl', $lessonplan['dpl'] ?? '') ?>">
                        </div>
                    </div>
                </div>

              
                    <h6 class="text-uppercase fw-bold text-success mb-3">Target Kurikulum</h6>
                    <div class="glass-card mb-4">
    <h6 class="text-uppercase fw-bold text-success mb-3">Target Kurikulum</h6>

    <!-- Agama -->
    <div class="mb-3">
        <label class="form-label small">Agama & Budi Pekerti</label>
        <select name="agama1" class="form-select mb-2 small shadow-sm">
            <option value="">-- Tujuan 1 --</option>
            <?php foreach ($agama1List as $t): ?>
                <option value="<?= esc($t['id']) ?>" <?= old('agama1', $lessonplan['agama1'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="agama2" class="form-select small shadow-sm">
            <option value="">-- Tujuan 2 --</option>
            <?php foreach ($agama1List as $t): ?>
                <option value="<?= esc($t['id']) ?>" <?= old('agama2', $lessonplan['agama2'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Literasi -->
    <div class="mb-3">
        <label class="form-label small">Literasi</label>
        <select name="literasi1" class="form-select mb-2 small shadow-sm">
            <option value="">-- Tujuan 1 --</option>
            <?php foreach ($literasiList as $t): ?>
                <option value="<?= esc($t['id']) ?>" <?= old('literasi1', $lessonplan['literasi1'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="literasi2" class="form-select small shadow-sm">
            <option value="">-- Tujuan 2 --</option>
            <?php foreach ($literasiList as $t): ?>
                <option value="<?= esc($t['id']) ?>" <?= old('literasi2', $lessonplan['literasi2'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Jati Diri -->
    <div class="mb-3">
        <label class="form-label small">Jati Diri</label>
        <select name="jatidiri1" class="form-select mb-2 small shadow-sm">
            <option value="">-- Tujuan 1 --</option>
            <?php foreach ($jatiList as $t): ?>
                <option value="<?= esc($t['id']) ?>" <?= old('jatidiri1', $lessonplan['jatidiri1'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="jatidiri2" class="form-select small shadow-sm">
            <option value="">-- Tujuan 2 --</option>
            <?php foreach ($jatiList as $t): ?>
                <option value="<?= esc($t['id']) ?>" <?= old('jatidiri2', $lessonplan['jatidiri2'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

</div>
             


            </div>

            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill" id="pills-prep-tab" data-bs-toggle="pill" data-bs-target="#pills-prep" type="button">Persiapan</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="pills-sambut-tab" data-bs-toggle="pill" data-bs-target="#pills-sambut" type="button">Kegiatan Sambut</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill" id="pills-inti-tab" data-bs-toggle="pill" data-bs-target="#pills-inti" type="button">Kegiatan Inti</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-prep" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">IKTP</label>
                                    <textarea name="iktp" class="form-control" rows="3"><?= old('iktp', $lessonplan['iktp'] ?? '') ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Pedagogis</label>
                                    <textarea name="pedagogis" class="form-control" rows="3"><?= old('pedagogis', $lessonplan['pedagogis'] ?? '') ?></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Alat, Bahan & Sumber</label>
                                    <textarea name="alatbahan" class="form-control mb-2" placeholder="Alat & Bahan..."><?= old('alatbahan', $lessonplan['alatbahan'] ?? '') ?></textarea>
                                    <textarea name="sumber" class="form-control" placeholder="Sumber Belajar..."><?= old('sumber', $lessonplan['sumber'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-sambut" role="tabpanel">
                            <div class="alert alert-info py-2 small">Input standard greeting activities for each day.</div>
                            <?php for($i=1; $i<=5; $i++): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Sambut <?= $i ?></label>
                                    <textarea name="sambut<?= $i ?>" class="form-control shadow-sm"><?= old("sambut$i", $lessonplan["sambut$i"] ?? '') ?></textarea>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="tab-pane fade" id="pills-inti" role="tabpanel">
                            <div class="mb-4 bg-light p-3 rounded shadow-sm">
                                <label class="form-label fw-bold text-primary">Pembukaan</label>
                                <textarea name="pembukaan" class="form-control bg-white" rows="5"><?= old('pembukaan', $lessonplan['pembukaan'] ?? '') ?></textarea>
                            </div>
                            <div class="row g-3">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Kegiatan Inti <?= $i ?></label>
                                        <textarea name="inti<?= $i ?>" class="form-control" rows="4" placeholder="Description for day <?= $i ?>..."><?= old("inti$i", $lessonplan["inti$i"] ?? '') ?></textarea>
                                    </div>
                                <?php endfor; ?>
                                <div class="col-12">
                                    <label class="form-label fw-bold text-danger">Penutup</label>
                                    <textarea name="penutup" class="form-control border-danger-subtle" rows="5"><?= old('penutup', $lessonplan['penutup'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .form-label { color: #495057; }
    .nav-pills .nav-link.active { background-color: #0d6efd; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); }
    .nav-pills .nav-link { color: #6c757d; font-weight: 500; }
    input[readonly] { cursor: not-allowed; }
</style>

<?= $this->endSection() ?>