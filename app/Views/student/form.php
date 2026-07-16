<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = isset($student);
$action = $isEdit ? base_url('student/save/'.$student['id']) : base_url('student/save');
$title  = $isEdit ? 'Edit Student' : 'Add Student';

// Kelas CSS agar seragam, bersih, dan konsisten
$inputClass  = "form-control form-control-sm bg-white text-dark border-secondary";
$selectClass = "form-select form-select-sm bg-white text-dark border-secondary";
?>

<div class="glass-card mx-auto shadow p-4 rounded">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
        <div>
            <h5 class="mb-1 text-white fw-bold"><?= $title ?></h5>
            <p class="text-white-50 small mb-0">Silakan lengkapi data siswa di bawah ini melalui tab yang tersedia.</p>
        </div>
        <span class="badge bg-info text-dark px-3 py-2 fs-7">Division ID: <?= esc($divisionId) ?></span>
    </div>

    <?php if (session('error')): ?>
        <div class="alert alert-danger py-2 small mb-3 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session('error') ?>
        </div>
    <?php endif; ?>

    <!-- Form Mulai -->
    <form method="post" action="<?= $action ?>" id="studentForm" class="needs-validation" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="division_id" value="<?= esc($divisionId) ?>">

        <!-- Navigasi Tab Visual -->
        <ul class="nav nav-pills custom-pills mb-4 gap-2 justify-content-center justify-content-md-start" id="studentFormTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active small px-3 py-2" id="primary-tab" data-bs-toggle="tab" data-bs-target="#primary-pane" type="button" role="tab">
                    <span class="step-num me-1">1</span> Biodata Utama
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small px-3 py-2" id="family-tab" data-bs-toggle="tab" data-bs-target="#family-pane" type="button" role="tab">
                    <span class="step-num me-1">2</span> Keluarga & Wali
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small px-3 py-2" id="school-tab" data-bs-toggle="tab" data-bs-target="#school-pane" type="button" role="tab">
                    <span class="step-num me-1">3</span> Akademik / Sekolah
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link small px-3 py-2" id="health-tab" data-bs-toggle="tab" data-bs-target="#health-pane" type="button" role="tab">
                    <span class="step-num me-1">4</span> Catatan & Medis
                </button>
            </li>
        </ul>

        <!-- Konten Formulir -->
        <div class="tab-content bg-dark bg-opacity-25 p-3 rounded border border-secondary" id="studentFormTabsContent">
            
            <!-- TAB 1: BIODATA UTAMA -->
            <div class="tab-pane fade show active" id="primary-pane" role="tabpanel" aria-labelledby="primary-tab" tabindex="0">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Student Code (Nomor Induk)</label>
                        <input type="text" name="student_code" value="<?= old('student_code', $student['student_code'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Contoh: 2026001">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small text-white-50 mb-1">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="<?= old('name', $student['name'] ?? '') ?>" class="<?= $inputClass ?>" required placeholder="Nama Lengkap Siswa">
                        <div class="invalid-feedback text-danger small mt-1">Nama lengkap wajib diisi.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Nickname</label>
                        <input type="text" name="nickname" value="<?= old('nickname', $student['nickname'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Nama Panggilan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="<?= $selectClass ?>">
                            <option value="">- Pilih Gender -</option>
                            <option value="L" <?= old('gender', $student['gender'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki (Male)</option>
                            <option value="P" <?= old('gender', $student['gender'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan (Female)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Birth Place</label>
                        <input type="text" name="birth_place" value="<?= old('birth_place', $student['birth_place'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Kota Kelahiran">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Birthdate</label>
                        <input type="date" name="birthdate" value="<?= old('birthdate', $student['birthdate'] ?? '') ?>" class="<?= $inputClass ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Nationality</label>
                        <input type="text" name="nationality" value="<?= old('nationality', $student['nationality'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Kewarganegaraan">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Religion</label>
                        <input type="text" name="murid_agama" value="<?= old('murid_agama', $student['murid_agama'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Agama">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Blood Type</label>
                        <input type="text" name="blood_type" value="<?= old('blood_type', $student['blood_type'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Golongan Darah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Language</label>
                        <input type="text" name="language" value="<?= old('language', $student['language'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Bahasa Sehari-hari">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Weight (kg)</label>
                        <input type="number" step="0.01" name="weight" value="<?= old('weight', $student['weight'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Berat Badan">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Height (cm)</label>
                        <input type="number" step="0.01" name="height" value="<?= old('height', $student['height'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Tinggi Badan">
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Address</label>
                        <textarea name="address" rows="2" class="<?= $inputClass ?>" placeholder="Alamat Lengkap Tinggal Siswa"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Photo Link / Path</label>
                        <input type="text" name="photo" value="<?= old('photo', $student['photo'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Foto Siswa">
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-sm btn-info text-dark px-4 next-tab-btn">Lanjut ke Keluarga <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- TAB 2: KELUARGA & WALI -->
            <div class="tab-pane fade" id="family-pane" role="tabpanel" aria-labelledby="family-tab" tabindex="0">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Child Order(Anak No)</label>
                        <input type="number" name="child_order" value="<?= old('child_order', $student['child_order'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Anak ke-berapa">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Family Status(Hubungan Keluarga)</label>
                        <input type="text" name="family_status" value="<?= old('family_status', $student['family_status'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Hubungan Keluarga">
                    </div>
                    
                    <div class="col-md-4 border-start border-primary border-2 ps-3">
                        <label class="form-label small text-white-50 mb-1 fw-bold text-white">Father's Name</label>
                        <input type="text" name="father_name" value="<?= old('father_name', $student['father_name'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Nama Ayah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Father's Education</label>
                        <input type="text" name="father_education" value="<?= old('father_education', $student['father_education'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Pendidikan Terakhir">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Father's Occupation</label>
                        <input type="text" name="father_occupation" value="<?= old('father_occupation', $student['father_occupation'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Pekerjaan Ayah">
                    </div>

                    <div class="col-md-4 border-start border-danger border-2 ps-3">
                        <label class="form-label small text-white-50 mb-1 fw-bold text-white">Mother's Name</label>
                        <input type="text" name="mother_name" value="<?= old('mother_name', $student['mother_name'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Nama Ibu">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Mother's Education</label>
                        <input type="text" name="mother_education" value="<?= old('mother_education', $student['mother_education'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Pendidikan Terakhir">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Mother's Occupation</label>
                        <input type="text" name="mother_occupation" value="<?= old('mother_occupation', $student['mother_occupation'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Pekerjaan Ibu">
                    </div>

                    <div class="col-md-6 border-start border-warning border-2 ps-3">
                        <label class="form-label small text-white-50 mb-1">Guardian Name (Wali)</label>
                        <input type="text" name="guardian_name" value="<?= old('guardian_name', $student['guardian_name'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Diisi jika tidak tinggal dengan ortu">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Guardian Relationship</label>
                        <input type="text" name="guardian_relationship" value="<?= old('guardian_relationship', $student['guardian_relationship'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Hubungan Wali dengan Siswa">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small text-white-50 mb-1">Parent Address</label>
                        <textarea name="parent_address" rows="2" class="<?= $inputClass ?>" placeholder="Alamat Rumah Orang Tua/Wali"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-white-50 mb-1">Parent Phone <span class="text-danger">*</span></label>
                        <input type="text" name="parent_phone" value="<?= old('parent_phone', $student['parent_phone'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Nomor Telepon Aktif">
                        <div class="invalid-feedback text-danger small mt-1">No. HP Orang Tua wajib diisi.</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-sm btn-outline-light px-3 prev-tab-btn"><i class="bi bi-arrow-left"></i> Kembali</button>
                    <button type="button" class="btn btn-sm btn-info text-dark px-4 next-tab-btn">Lanjut ke Akademik <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- TAB 3: AKADEMIK & SEKOLAH -->
            <div class="tab-pane fade" id="school-pane" role="tabpanel" aria-labelledby="school-tab" tabindex="0">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Assigned Class <span class="text-danger">*</span></label>
                        <select name="class_id" class="<?= $selectClass ?>">
                            <option value="">- Pilih Kelas -</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= old('class_id', $student['class_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                    <?= esc($c['grade_name'].' - '.$c['class_name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <div class="invalid-feedback text-danger small mt-1">Kelas wajib dipilih.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Group (Rombel)</label>
                        <input type="text" name="group_name" value="<?= old('group_name', $student['group_name'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Grup Kelas">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Admission Date (Tanggal Masuk)</label>
                        <input type="date" name="admission_date" value="<?= old('admission_date', $student['admission_date'] ?? '') ?>" class="<?= $inputClass ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Admission Age (Umur Saat Masuk)</label>
                        <input type="text" name="admission_age" value="<?= old('admission_age', $student['admission_age'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Contoh: 6 Tahun 2 Bulan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Exit Date (Tanggal Keluar)</label>
                        <input type="date" name="exit_date" value="<?= old('exit_date', $student['exit_date'] ?? '') ?>" class="<?= $inputClass ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-white-50 mb-1">Next School Reference (Sekolah Lanjutan)</label>
                        <input type="text" name="next_school" value="<?= old('next_school', $student['next_school'] ?? '') ?>" class="<?= $inputClass ?>" placeholder="Nama Sekolah Selanjutnya">
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Exit Reason (Alasan Keluar)</label>
                        <textarea name="exit_reason" rows="2" class="<?= $inputClass ?>" placeholder="Alasan jika siswa mengundurkan diri / lulus"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-sm btn-outline-light px-3 prev-tab-btn"><i class="bi bi-arrow-left"></i> Kembali</button>
                    <button type="button" class="btn btn-sm btn-info text-dark px-4 next-tab-btn">Lanjut ke Catatan Medis <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- TAB 4: CATATAN & KESEHATAN -->
            <div class="tab-pane fade" id="health-pane" role="tabpanel" aria-labelledby="health-tab" tabindex="0">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Medical History (Riwayat Penyakit)</label>
                        <textarea name="medical_history" rows="2" class="<?= $inputClass ?>" placeholder="Tulis riwayat penyakit penting jika ada"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Immunization History (Riwayat Imunisasi)</label>
                        <textarea name="immunization_history" rows="2" class="<?= $inputClass ?>" placeholder="Daftar vaksinasi yang sudah diterima"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Speech Development (Perkembangan Bicara)</label>
                        <textarea name="speech_development" rows="2" class="<?= $inputClass ?>" placeholder="Catatan tumbuh kembang cara berkomunikasi siswa"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Physical Condition (Kondisi Fisik)</label>
                        <textarea name="physical_condition" rows="2" class="<?= $inputClass ?>" placeholder="Keadaan fisik khusus / disabilitas jika ada"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Achievements (Prestasi)</label>
                        <textarea name="achievements" rows="2" class="<?= $inputClass ?>" placeholder="Penghargaan atau bakat spesial"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Development Notes (Catatan Perkembangan Akademik)</label>
                        <textarea name="development_notes" rows="2" class="<?= $inputClass ?>" placeholder="Catatan khusus dari wali kelas sebelumnya"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small text-white-50 mb-1">Remarks (Keterangan Lainnya)</label>
                        <textarea name="remarks" rows="2" class="<?= $inputClass ?>" placeholder="Catatan opsional tambahan"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <button type="button" class="btn btn-sm btn-outline-light px-3 prev-tab-btn"><i class="bi bi-arrow-left"></i> Kembali</button>
                </div>
            </div>

        </div>

        <!-- Tombol Aksi Akhir -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
            <a href="<?= base_url('student?division='.$divisionId) ?>" class="btn btn-sm btn-outline-light px-3">
                Cancel
            </a>
            <button type="submit" class="btn btn-sm btn-success px-4 shadow-sm" id="submitFormBtn">
                <i class="bi bi-check-circle-fill me-1"></i> <?= $isEdit ? 'Update' : 'Save' ?> Student
            </button>
        </div>
    </form>
</div>

<!-- Styling Tab & Interaksi Dinamis -->
<style>
/* Modifikasi UI Tab agar mirip tombol navigasi modern */
.custom-pills .nav-link {
    border: 1px solid rgba(255,255,255,0.15) !important;
    background: rgba(255, 255, 255, 0.05);
    color: rgba(255,255,255,0.7) !important;
    border-radius: 6px;
    transition: all 0.2s ease;
}
.custom-pills .nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #fff !important;
}
.custom-pills .nav-link.active {
    background-color: #0d6efd !important; /* Warna Biru Utama */
    color: #fff !important;
    border-color: #0d6efd !important;
    font-weight: 600;
}
.custom-pills .step-num {
    background: rgba(0,0,0,0.25);
    border-radius: 50%;
    padding: 1px 6px;
    font-size: 0.8em;
}
.custom-pills .nav-link.active .step-num {
    background: rgba(255,255,255,0.25);
}
</style>

<!-- JavaScript untuk Navigasi Next/Prev + Validasi Sederhana -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("studentForm");
    const nextButtons = document.querySelectorAll(".next-tab-btn");
    const prevButtons = document.querySelectorAll(".prev-tab-btn");
    const tabElements = document.querySelectorAll('#studentFormTabs button[data-bs-toggle="tab"]');

    // 1. Logika Klik tombol "Lanjut / Next"
    nextButtons.forEach(button => {
        button.addEventListener("click", function () {
            let activeTab = document.querySelector('#studentFormTabs button.active');
            let nextTab = activeTab.closest('li').nextElementSibling?.querySelector('button');
            if (nextTab) {
                let tab = new bootstrap.Tab(nextTab);
                tab.show();
            }
        });
    });

    // 2. Logika Klik tombol "Kembali / Prev"
    prevButtons.forEach(button => {
        button.addEventListener("click", function () {
            let activeTab = document.querySelector('#studentFormTabs button.active');
            let prevTab = activeTab.closest('li').previousElementSibling?.querySelector('button');
            if (prevTab) {
                let tab = new bootstrap.Tab(prevTab);
                tab.show();
            }
        });
    });

    // 3. Validasi Form HTML5 (Menandai Input Kosong Sebelum Dikirim)
    form.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Mencari tab pertama yang memiliki kolom error (kosong tetapi wajib)
            let firstInvalidInput = form.querySelector(':invalid');
            if (firstInvalidInput) {
                let pane = firstInvalidInput.closest('.tab-pane');
                let tabId = pane.getAttribute('aria-labelledby');
                let tabBtn = document.getElementById(tabId);
                
                // Pindah fokus secara otomatis ke tab yang salah
                if (tabBtn) {
                    let tab = new bootstrap.Tab(tabBtn);
                    tab.show();
                }
            }
        }
        form.classList.add("was-validated");
    }, false);
});
</script>

<?= $this->endSection() ?>