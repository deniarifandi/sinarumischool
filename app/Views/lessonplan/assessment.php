<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Penilaian Pembelajaran</h3>
            <p class="text-muted mb-0">
                Penilaian perkembangan siswa berdasarkan lesson plan
            </p>
        </div>

        <a href="<?= base_url('lessonplan?subject_id='.$lessonplan['subject_id']) ?>"
           class="btn btn-light border rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>


    <!-- RINGKASAN LESSON PLAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <h5 class="fw-bold text-primary mb-4">
                <i class="bi bi-journal-text me-2"></i>
                Ringkasan Pembelajaran
            </h5>

            <div class="row g-3">

                <div class="col-md-3">
                    <div class="text-muted small">Kelas</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['class_name'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Mata Pelajaran</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['subject_name'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Topik</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['unit_name'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Sub Topik</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['subunit_name'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Semester</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['semester'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Bulan</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['bulan'] ?? '-') ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="text-muted small">Guru</div>
                    <div class="fw-bold">
                        <?= esc($lessonplan['teacher_name'] ?? '-') ?>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- TUJUAN / STRATEGI -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <h5 class="fw-bold text-primary mb-4">
                <i class="bi bi-bullseye me-2"></i>
                Tujuan & Strategi Pembelajaran
            </h5>

            <div class="row g-4">

                <div class="col-lg-6">

                    <h6 class="fw-bold">Tujuan / Capaian</h6>

                    <ul class="mb-0">

                        <?php if (!empty($lessonplan['agama1_name'])): ?>
                            <li><?= esc($lessonplan['agama1_name']) ?></li>
                        <?php endif ?>

                        <?php if (!empty($lessonplan['agama2_name'])): ?>
                            <li><?= esc($lessonplan['agama2_name']) ?></li>
                        <?php endif ?>

                        <?php if (!empty($lessonplan['jati1_name'])): ?>
                            <li><?= esc($lessonplan['jati1_name']) ?></li>
                        <?php endif ?>

                        <?php if (!empty($lessonplan['jati2_name'])): ?>
                            <li><?= esc($lessonplan['jati2_name']) ?></li>
                        <?php endif ?>

                        <?php if (!empty($lessonplan['dasar1_name'])): ?>
                            <li><?= esc($lessonplan['dasar1_name']) ?></li>
                        <?php endif ?>

                        <?php if (!empty($lessonplan['dasar2_name'])): ?>
                            <li><?= esc($lessonplan['dasar2_name']) ?></li>
                        <?php endif ?>

                    </ul>

                </div>


                <div class="col-lg-6">

                    <h6 class="fw-bold">Strategi Pembelajaran</h6>

                    <?php if (!empty($lessonplan['pedagogis'])): ?>
                        <div class="mb-2">
                            <span class="text-muted">Pendekatan:</span>
                            <?= esc($lessonplan['pedagogis']) ?>
                        </div>
                    <?php endif ?>

                    <?php if (!empty($lessonplan['kemitraan'])): ?>
                        <div class="mb-2">
                            <span class="text-muted">Kemitraan:</span>
                            <?= esc($lessonplan['kemitraan']) ?>
                        </div>
                    <?php endif ?>

                    <?php if (!empty($lessonplan['alatbahan'])): ?>
                        <div class="mb-2">
                            <span class="text-muted">Alat & Bahan:</span>
                            <?= esc($lessonplan['alatbahan']) ?>
                        </div>
                    <?php endif ?>

                </div>

            </div>

        </div>
    </div>


    <!-- PENILAIAN -->
    <form action="<?= base_url('lessonplan/assessment/'.$lessonplan['id']) ?>"
          method="post">

        <?= csrf_field() ?>

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h5 class="fw-bold mb-1">
                            Penilaian Siswa
                        </h5>

                        <div class="text-muted small">
                            <?= count($students) ?> siswa
                        </div>
                    </div>

                 <div class="d-flex gap-2">

    <a href="<?= base_url('lessonplan/assessment/print/'.$lessonplan['id']) ?>"
       target="_blank"
       class="btn btn-secondary rounded-pill px-4">
        <i class="bi bi-printer me-1"></i>
        Cetak
    </a>

    <button type="submit"
            class="btn btn-success rounded-pill px-4">
        <i class="bi bi-check2-circle me-1"></i>
        Simpan Penilaian
    </button>

</div>

                </div>


                <!-- LEGEND -->

                <div class="bg-light rounded-3 p-3 mb-4">

                    <div class="row g-2 small">

                        <div class="col-md-3">
                            <strong>1</strong> — Mulai Berkembang
                        </div>

                        <div class="col-md-3">
                            <strong>2</strong> — Berkembang
                        </div>

                        <div class="col-md-3">
                            <strong>3</strong> — Berkembang Sesuai Harapan
                        </div>

                        <div class="col-md-3">
                            <strong>4</strong> — Sangat Berkembang
                        </div>

                    </div>

                </div>


                <!-- STUDENTS -->

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Siswa</th>
                                <th class="text-center">
                                    Mulai Berkembang
                                </th>
                                <th class="text-center">
                                    Berkembang
                                </th>
                                <th class="text-center">
                                    BSH
                                </th>
                                <th class="text-center">
                                    Sangat Berkembang
                                </th>
                                <th style="min-width:300px">
                                    Catatan
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if (empty($students)): ?>

                            <tr>
                                <td colspan="7"
                                    class="text-center text-muted py-5">
                                    Belum ada siswa pada kelas ini.
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($students as $i => $student): ?>

                                <?php
                                $studentId = $student['id'];
                                $assessment = $assessmentMap[$studentId] ?? [];
                                $currentScore = $assessment['score'] ?? '3';
                                $currentNotes = $assessment['notes'] ?? '';
                                ?>

                                <tr>

                                    <td>
                                        <?= $i + 1 ?>
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($student['name']) ?>
                                        </div>
                                    </td>


                                    <!-- SCORE 1 -->

                                    <td class="text-center">

                                        <input type="radio"
                                               class="form-check-input"
                                               name="score[<?= $studentId ?>]"
                                               value="1"
                                               style="transform:scale(1.3)"
                                            <?= $currentScore == 1 ? 'checked' : '' ?>>

                                    </td>


                                    <!-- SCORE 2 -->

                                    <td class="text-center">

                                        <input type="radio"
                                               class="form-check-input"
                                               name="score[<?= $studentId ?>]"
                                               value="2"
                                               style="transform:scale(1.3)"
                                            <?= $currentScore == 2 ? 'checked' : '' ?>>

                                    </td>


                                    <!-- SCORE 3 -->

                                    <td class="text-center">

                                        <input type="radio"
                                               class="form-check-input"
                                               name="score[<?= $studentId ?>]"
                                               value="3"
                                               style="transform:scale(1.3)"
                                            <?= $currentScore == 3 ? 'checked' : '' ?>>

                                    </td>


                                    <!-- SCORE 4 -->

                                    <td class="text-center">

                                        <input type="radio"
                                               class="form-check-input"
                                               name="score[<?= $studentId ?>]"
                                               value="4"
                                               style="transform:scale(1.3)"
                                            <?= $currentScore == 4 ? 'checked' : '' ?>>

                                    </td>


                                    <!-- NOTES -->

                                    <td>

                                        <input type="text"
                                               name="notes[<?= $studentId ?>]"
                                               class="form-control"
                                               placeholder="Catatan perkembangan..."
                                               value="<?= esc($currentNotes) ?>">

                                    </td>

                                </tr>

                            <?php endforeach ?>

                        <?php endif ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </form>

</div>
<?= $this->endSection() ?>