<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','December'];

$dplOptions = [
    1 => 'Beriman & Bertakwa', 2 => 'Mandiri', 4 => 'Bernalar Kritis', 8 => 'Kreatif',
    16 => 'Gotong Royong', 32 => 'Berkebinekaan Global', 64 => 'Komunikatif', 128 => 'Berakhlak Mulia'
];
$selectedDpl = isset($lessonplan['dpl']) ? (int)$lessonplan['dpl'] : 0;

$intiOptions = [1 => 'Mindful (Fokus)', 2 => 'Meaningful (Bermakna)', 4 => 'Joyful (Menyenangkan)'];
$selectedInti = isset($lessonplan['inti']) ? (int)$lessonplan['inti'] : 0;

$defaultText = [
    'pedagogis'  => 'Guru menggunakan pendekatan pembelajaran aktif dan menyenangkan.',
    'kemitraan'  => 'Melibatkan komunikasi dengan orang tua terkait kegiatan belajar.',
    'alatbahan'  => 'Kertas, alat tulis, media pembelajaran dan bahan pendukung.',
    'sumber'     => 'Lingkungan sekitar, buku cerita, dan media visual.',
    'pembukaan'  => "- Masuk kelas dan berdoa bersama.\n- Membuat kesepakatan bersama.\n- Apersepsi.",
    'penutup'    => 'Guru melakukan refleksi dan menutup kegiatan belajar.',
    'inti1'      => 'Guru menjelaskan kegiatan.',
    'inti2'      => 'Peserta didik melakukan observasi.',
    'inti3'      => 'Peserta didik mencoba kegiatan.',
    'inti4'      => 'Guru mendampingi kegiatan.',
    'inti5'      => 'Peserta didik menyimpulkan kegiatan.',
    'assessment' => 'observasi, checklist, hasil karya',
];

$fieldLabels = [
    'pedagogis'  => 'Strategi Mengajar (Pedagogis)',
    'kemitraan'  => 'Kerjasama dengan Orang Tua',
    'alatbahan'  => 'Alat dan Bahan yang Diperlukan',
    'sumber'     => 'Sumber Belajar / Referensi',
    'pembukaan'  => 'Kegiatan Pembukaan Kelas',
    'penutup'    => 'Kegiatan Penutup Kelas',
    'inti1'      => 'Kegiatan Hari Senin',
    'inti2'      => 'Kegiatan Hari Selasa',
    'inti3'      => 'Kegiatan Hari Rabu',
    'inti4'      => 'Kegiatan Hari Kamis',
    'inti5'      => 'Kegiatan Hari Jumat',
    'assessment' => 'Cara Penilaian (Assessment)',
];

$fields = ['pedagogis', 'kemitraan', 'alatbahan', 'sumber', 'pembukaan', 'penutup', 'inti1', 'inti2', 'inti3', 'inti4', 'inti5', 'assessment'];
?>

<div class="container-fluid py-4" style="max-width: 1300px;">
    <div class="card shadow border-0 rounded-3 backend-card">
        <!-- HEADER FORM -->
        <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1 text-dark"><?= isset($lessonplan) ? 'Ubah' : 'Buat' ?> Rencana Pembelajaran (Lesson Plan)</h3>
                <p class="text-muted mb-0 fs-6">Silakan isi formulir di bawah ini dengan lengkap. Tanda pilihan sudah disediakan untuk mempermudah Anda.</p>
            </div>
            <a href="<?= base_url('lessonplan') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-medium">◀ Kembali</a>
        </div>

        <div class="card-body p-4 bg-light-gradient">
            <form action="<?= isset($lessonplan) ? base_url('lessonplan/update/'.$lessonplan['id']) : base_url('lessonplan/store') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="class_id" value="<?= esc($mainClass['id']) ?>">
                <input type="hidden" name="subject_id" value="<?= esc(request()->getGet('subject_id')) ?>">

                <div class="row g-4">
                    <!-- KOLOM KIRI: INFORMASI UTAMA & PILIHAN CAPAIAN -->
                    <div class="col-lg-5">
                        <div class="bg-white p-4 rounded-3 shadow-sm border border-light h-100">
                            <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">1. Informasi Utama</h5>
                            
                            <!-- Topik -->
                            <div class="mb-3">
                                <label for="unit_id" class="form-label fw-bold text-dark mb-1">Pilih Topik Utama</label>
                                <select name="unit_id" id="unit_id" class="form-select form-select-lg text-dark" required>
                                    <option value="">-- Klik di sini untuk memilih Topik --</option>
                                    <?php foreach ($units as $u): ?>
                                        <option value="<?= $u['id'] ?>" <?= ($lessonplan['unit_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                                            <?= esc($u['name']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <!-- Sub Topik -->
                            <div class="mb-3">
                                <label for="subunit_id" class="form-label fw-bold text-dark mb-1">Pilih Sub Topik</label>
                                <select name="subunit_id" id="subunit_id" class="form-select form-select-lg text-dark" required <?= empty($lessonplan['unit_id']) ? 'disabled' : '' ?>>
                                    <option value="">-- Pilih Topik Utama Terlebih Dahulu --</option>
                                    <?php foreach ($subunits as $s): ?>
                                        <option value="<?= $s['id'] ?>" <?= ($lessonplan['subunit_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                                            <?= esc($s['subunit_name']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <!-- Semester & Bulan -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="semester" class="form-label fw-bold text-dark mb-1">Semester</label>
                                    <select name="semester" id="semester" class="form-select form-select-lg text-dark" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="1" <?= ($lessonplan['semester'] ?? '') == '1' ? 'selected' : '' ?>>Semester 1</option>
                                        <option value="2" <?= ($lessonplan['semester'] ?? '') == '2' ? 'selected' : '' ?>>Semester 2</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="bulan" class="form-label fw-bold text-dark mb-1">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-select form-select-lg text-dark" required>
                                        <option value="">-- Pilih --</option>
                                        <?php foreach ($bulanList as $b): ?>
                                            <option value="<?= $b ?>" <?= ($lessonplan['bulan'] ?? '') == $b ? 'selected' : '' ?>><?= $b ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>

                            <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom mt-4">2. Target Kompetensi (Centang yang sesuai)</h5>

                            <!-- DPL Checkboxes -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Profil Lulusan (DPL)</label>
                                <div class="row g-2">
                                    <?php foreach ($dplOptions as $val => $label): ?>
                                        <div class="col-sm-6">
                                            <label class="form-check p-3 border rounded bg-light item-checkbox d-flex align-items-center" style="min-height: 55px;">
                                                <input class="form-check-input me-3" type="checkbox" name="dpl[]" id="dpl_<?= $val ?>" value="<?= $val ?>" <?= ($selectedDpl & $val) ? 'checked' : '' ?> style="transform: scale(1.3);">
                                                <span class="text-dark fw-medium lh-sm fs-6"><?= $label ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>

                            <!-- Karakter Checkboxes -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark mb-2">Karakter Pembelajaran</label>
                                <div class="d-flex flex-column gap-2">
                                    <?php foreach ($intiOptions as $val => $label): ?>
                                        <label class="form-check p-3 border rounded bg-light item-checkbox d-flex align-items-center">
                                            <input class="form-check-input me-3" type="checkbox" name="inti[]" id="inti_<?= $val ?>" value="<?= $val ?>" <?= ($selectedInti & $val) ? 'checked' : '' ?> style="transform: scale(1.3);">
                                            <span class="text-dark fw-medium fs-6"><?= esc($label) ?></span>
                                        </label>
                                    <?php endforeach ?>
                                </div>
                            </div>

                            <!-- Dropdown Nilai Capaian -->
                            <div class="p-3 rounded-3 border bg-light">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark mb-1">Nilai Agama & Moral</label>
                                    <select name="agama1" class="form-select mb-2 text-dark select-sm-custom">
                                        <option value="">-- Pilih Capaian 1 (Opsional) --</option>
                                        <?php foreach ($agama as $a): ?>
                                            <option value="<?= $a['id'] ?>" <?= ($lessonplan['agama1'] ?? '') == $a['id'] ? 'selected' : '' ?>><?= esc($a['objective_name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <select name="agama2" class="form-select text-dark select-sm-custom">
                                        <option value="">-- Pilih Capaian 2 (Opsional) --</option>
                                        <?php foreach ($agama as $a): ?>
                                            <option value="<?= $a['id'] ?>" <?= ($lessonplan['agama2'] ?? '') == $a['id'] ? 'selected' : '' ?>><?= esc($a['objective_name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark mb-1">Pengembangan Jati Diri</label>
                                    <select name="jati1" class="form-select mb-2 text-dark select-sm-custom">
                                        <option value="">-- Pilih Capaian 1 (Opsional) --</option>
                                        <?php foreach ($jati as $j): ?>
                                            <option value="<?= $j['id'] ?>" <?= ($lessonplan['jati1'] ?? '') == $j['id'] ? 'selected' : '' ?>><?= esc($j['objective_name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <select name="jati2" class="form-select text-dark select-sm-custom">
                                        <option value="">-- Pilih Capaian 2 (Opsional) --</option>
                                        <?php foreach ($jati as $j): ?>
                                            <option value="<?= $j['id'] ?>" <?= ($lessonplan['jati2'] ?? '') == $j['id'] ? 'selected' : '' ?>><?= esc($j['objective_name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label fw-bold text-dark mb-1">Dasar Literasi & STEAM</label>
                                    <select name="dasar1" class="form-select mb-2 text-dark select-sm-custom">
                                        <option value="">-- Pilih Capaian 1 (Opsional) --</option>
                                        <?php foreach ($literasi as $l): ?>
                                            <option value="<?= $l['id'] ?>" <?= ($lessonplan['dasar1'] ?? '') == $l['id'] ? 'selected' : '' ?>><?= esc($l['objective_name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <select name="dasar2" class="form-select text-dark select-sm-custom">
                                        <option value="">-- Pilih Capaian 2 (Opsional) --</option>
                                        <?php foreach ($literasi as $l): ?>
                                            <option value="<?= $l['id'] ?>" <?= ($lessonplan['dasar2'] ?? '') == $l['id'] ? 'selected' : '' ?>><?= esc($l['objective_name']) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: DETAIL RENCANA HARI / TEXTAREA -->
                    <div class="col-lg-7">
                        <div class="bg-white p-4 rounded-3 shadow-sm border border-light h-100">
                            <h5 class="fw-bold text-primary mb-4 pb-2 border-bottom">3. Detail Pelaksanaan Pembelajaran</h5>
                            <p class="text-muted small mb-3">⚠️ <strong>Petunjuk:</strong> Anda dapat mengubah contoh teks di bawah ini sesuai dengan rencana pembelajaran yang Anda inginkan.</p>
                            
                            <div class="row g-3">
                                <?php foreach ($fields as $f): ?>
                                    <?php 
                                    $isDaily = strpos($f, 'inti') !== false;
                                    $colClass = $isDaily ? 'col-md-6' : 'col-12';
                                    ?>
                                    <div class="<?= $colClass ?>">
                                        <div class="mb-2">
                                            <label for="field_<?= $f ?>" class="form-label fw-bold text-dark mb-1 fs-6">
                                                <?= $fieldLabels[$f] ?? $f ?>
                                            </label>
                                            <textarea name="<?= $f ?>" id="field_<?= $f ?>" class="form-control text-dark fs-6 font-monospace-custom" style="height: 120px; border: 1px solid #ced4da;" placeholder="Contoh: <?= esc($defaultText[$f] ?? '') ?>"><?= $lessonplan[$f] ?? ($defaultText[$f] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT ACTION -->
                <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                    <button type="reset" class="btn btn-lg btn-light px-4 rounded-pill border fw-medium fs-6 text-secondary">Kosongkan Ulang Form</button>
                    <button type="submit" class="btn btn-lg btn-success px-5 rounded-pill shadow fw-bold fs-5">
                        💾 <?= isset($lessonplan) ? 'Simpan Perubahan' : 'Simpan Rencana Belajar' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-light-gradient {
        background-color: #f8fafc;
    }
    .form-select-lg {
        font-size: 1rem !important;
        padding-top: 0.6rem !important;
        padding-bottom: 0.6rem !important;
    }
    .select-sm-custom {
        font-size: 0.9rem !important;
        padding: 0.5rem !important;
    }
    .item-checkbox {
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        user-select: none;
    }
    .item-checkbox:hover {
        border-color: #4f46e5 !important;
        background-color: #f0fdf4 !important; /* Hijau muda yang nyaman di mata */
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .form-check-input:checked + span {
        font-weight: bold !important;
        color: #198754 !important;
    }
    textarea.form-control:focus, select.form-select:focus {
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25) !important;
    }
    .font-monospace-custom {
        font-family: system-ui, -apple-system, sans-serif;
        line-height: 1.5;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const unitSelect = document.getElementById('unit_id');
    const subSelect = document.getElementById('subunit_id');
    const selectedSubunit = '<?= $lessonplan['subunit_id'] ?? '' ?>';

    const loadSubunits = async (unitId) => {
        if (!unitId) {
            subSelect.disabled = true;
            subSelect.innerHTML = '<option value="">-- Pilih Topik Utama Terlebih Dahulu --</option>';
            return;
        }

        subSelect.disabled = false;
        subSelect.innerHTML = '<option>Sedang memuat data pilihan... Mohon tunggu</option>';

        try {
            const response = await fetch(`<?= base_url('lessonplan/subunits') ?>/${unitId}`);
            if (!response.ok) throw new Error('Gagal mengambil data');
            
            const data = await response.json();
            
            subSelect.innerHTML = '<option value="">-- Klik di sini untuk memilih Sub Topik --</option>';
            data.forEach(item => {
                const isSelected = item.id == selectedSubunit ? 'selected' : '';
                subSelect.insertAdjacentHTML('beforeend', `
                    <option value="${item.id}" ${isSelected}>
                        ${item.subunit_name}
                    </option>
                `);
            });
        } catch (error) {
            console.error(error);
            subSelect.innerHTML = '<option value="">Gagal memuat data, silahkan coba pilih lagi topik diatas</option>';
        }
    };

    if (unitSelect.value) {
        loadSubunits(unitSelect.value);
    }

    unitSelect.addEventListener('change', (e) => {
        loadSubunits(e.target.value);
    });
});
</script>

<?= $this->endSection() ?>