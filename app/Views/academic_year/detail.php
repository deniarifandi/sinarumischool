<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<!-- HEADER SECTION -->
<div class="card bg-light border-secondary shadow-sm mb-4">
    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="text-dark mb-0 fw-bold"><?= esc($academicYear['name']) ?></h4>
                <?= $academicYear['is_active'] ? '<span class="badge bg-success rounded-pill px-3">Aktif</span>' : '' ?>
            </div>
            <div class="text-dark-50 small">
                <i class="bi bi-calendar-range me-1"></i> 
                <?= esc($academicYear['start_date']) ?> <span class="mx-1">s/d</span> <?= esc($academicYear['end_date']) ?>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('academic-year') ?>" class="btn btn-outline-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSemesterModal">
                <i class="bi bi-plus-lg me-1"></i> Tambah Semester
            </button>
        </div>
    </div>
</div>

<!-- EMPTY STATE SEMESTER -->
<?php if (empty($semesters)): ?>
    <div class="text-center py-5 bg-light border border-secondary rounded shadow-sm">
        <i class="bi bi-folder2-open display-4 text-secondary mb-3"></i>
        <h5 class="text-dark">Belum Ada Semester</h5>
        <p class="text-dark-50 small mb-0">Silakan tambah semester untuk tahun ajaran ini terlebih dahulu.</p>
    </div>
<?php endif; ?>

<!-- SEMESTER LIST (FLAT CARDS) -->
<?php foreach ($semesters as $i => $semester): ?>
    <?php $terms = $termsBySemester[$semester['id']] ?? []; ?>
    
    <div class="card bg-light border-secondary mb-4 shadow-sm">
        <!-- Semester Header -->
        <div class="card-header border-secondary d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-3">
            <div class="d-flex align-items-center mb-2 mb-sm-0">
                <i class="bi bi-journal-bookmark-fill text-primary fs-4 me-3"></i>
                <div>
                    <h5 class="text-dark mb-0 fw-bold"><?= esc($semester['name']) ?></h5>
                    <small class="text-dark-50">Semester <?= esc($semester['number']) ?> &bull; <?= count($terms) ?> Term</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#editSemester<?= $semester['id'] ?>">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTerm<?= $semester['id'] ?>">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Term
                </button>
            </div>
        </div>

        <!-- Term Data -->
        <div class="card-body p-0">
            <?php if (empty($terms)): ?>
                <div class="text-center py-4">
                    <p class="text-dark-50 small mb-0">Belum ada term di semester ini.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-secondary align-middle mb-0">
                        <thead class="table-active">
                            <tr>
                                <th class="text-center border-secondary" width="5%">#</th>
                                <th class="border-secondary">Nama Term</th>
                                <th class="border-secondary">Periode</th>
                                <th class="border-secondary">Status</th>
                                <th class="text-end border-secondary" width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($terms as $term): ?>
                            <tr>
                                <td class="text-center fw-bold text-dark-50 border-secondary"><?= esc($term['number']) ?></td>
                                <td class="fw-semibold border-secondary"><?= esc($term['name']) ?></td>
                                <td class="text-dark-50 small border-secondary">
                                    <?= date('d M Y', strtotime($term['start_date'])) ?> &mdash; <?= date('d M Y', strtotime($term['end_date'])) ?>
                                </td>
                                <td class="border-secondary">
                                    <?= $term['is_locked']
                                        ? '<span class="badge bg-danger bg-opacity-25 text-danger border border-danger rounded-pill px-2 py-1"><i class="bi bi-lock-fill me-1"></i> Locked</span>'
                                        : '<span class="badge bg-success bg-opacity-25 text-success border border-success rounded-pill px-2 py-1"><i class="bi bi-unlock-fill me-1"></i> Open</span>' ?>
                                </td>
                                <td class="text-end border-secondary">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTerm<?= $term['id'] ?>" title="Edit Term">
                                            <i class="bi bi-pencil">edit</i>
                                        </button>
                                        <a href="<?= base_url('lock/terms/' . $term['id']) ?>" class="btn btn-sm btn-info" title="Kelola Kunci">
                                            <i class="bi bi-shield-lock">Override</i>
                                        </a>
                                        <?php if ($term['is_locked']): ?>
                                            <form method="post" action="<?= base_url('lock/terms/' . $term['id'] . '/unlock') ?>" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-success" title="Buka Kunci" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="bi bi-unlock">Un-Lock</i></button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" action="<?= base_url('lock/terms/' . $term['id'] . '/lock') ?>" class="d-inline"
                                                  onsubmit="return confirm('Kunci semua gradebook di term ini?')">
                                                <?= csrf_field() ?>
                                                <button class="btn btn-sm btn-danger" title="Kunci" style="border-top-left-radius: 0; border-bottom-left-radius: 0;"><i class="bi bi-lock">Lock</i></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<!-- ============================================== -->
<!-- MODALS SECTION (Placed safely outside the loop wrapper) -->
<!-- ============================================== -->

<?php foreach ($semesters as $semester): ?>
    <?php $terms = $termsBySemester[$semester['id']] ?? []; ?>
    
    <!-- Edit Semester Modal -->
    <div class="modal fade" id="editSemester<?= $semester['id'] ?>" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('academic-year/semester/' . $semester['id'] . '/update') ?>" class="modal-content bg-light text-dark shadow-lg border-secondary">
            <?= csrf_field() ?>
            <div class="modal-header border-secondary">
                <h6 class="modal-title fw-bold">Edit Semester</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label text-dark-50 small">Nomor</label>
                    <input type="number" name="number" class="form-control bg-light text-dark border-secondary" value="<?= esc($semester['number']) ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label text-dark-50 small">Nama Semester</label>
                    <input type="text" name="name" class="form-control bg-light text-dark border-secondary" value="<?= esc($semester['name']) ?>" required>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
            </div>
        </form>
      </div>
    </div>

    <!-- Add Term Modal -->
    <div class="modal fade" id="addTerm<?= $semester['id'] ?>" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="<?= base_url('academic-year/semester/' . $semester['id'] . '/term/add') ?>" class="modal-content bg-light text-dark shadow-lg border-secondary">
            <?= csrf_field() ?>
            <div class="modal-header border-secondary">
                <h6 class="modal-title fw-bold">Tambah Term <span class="text-primary">- <?= esc($semester['name']) ?></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-4">
                    <label class="form-label text-dark-50 small">Nomor</label>
                    <input type="number" name="number" class="form-control bg-light text-dark border-secondary" placeholder="1" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label text-dark-50 small">Nama Term</label>
                    <input type="text" name="name" class="form-control bg-light text-dark border-secondary" placeholder="cth: Term 1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-dark-50 small">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control bg-light text-dark border-secondary" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-dark-50 small">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control bg-light text-dark border-secondary" required>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary px-4">Tambah Term</button>
            </div>
        </form>
      </div>
    </div>

    <!-- Edit Term Modals -->
    <?php foreach ($terms as $term): ?>
        <div class="modal fade" id="editTerm<?= $term['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="<?= base_url('academic-year/term/' . $term['id'] . '/update') ?>" class="modal-content bg-light text-dark shadow-lg border-secondary">
                <?= csrf_field() ?>
                <div class="modal-header border-secondary">
                    <h6 class="modal-title fw-bold">Edit Term</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($term['is_locked']): ?>
                    <div class="alert alert-warning d-flex align-items-center small py-2 mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div>Term ini sedang terkunci. Tanggal masih bisa diedit, tapi buka kunci lewat "Kelola Kunci" bila perlu ubah nilai.</div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-dark-50 small">Nomor</label>
                            <input type="number" name="number" class="form-control bg-light text-dark border-secondary" value="<?= esc($term['number']) ?>" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-dark-50 small">Nama Term</label>
                            <input type="text" name="name" class="form-control bg-light text-dark border-secondary" value="<?= esc($term['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark-50 small">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control bg-light text-dark border-secondary" value="<?= esc($term['start_date']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark-50 small">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control bg-light text-dark border-secondary" value="<?= esc($term['end_date']) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
          </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<!-- Add Semester Modal -->
<div class="modal fade" id="addSemesterModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="post" action="<?= base_url('academic-year/' . $academicYear['id'] . '/semester/add') ?>" class="modal-content bg-light text-dark shadow-lg border-secondary">
        <?= csrf_field() ?>
        <div class="modal-header border-secondary">
            <h6 class="modal-title fw-bold">Tambah Semester Baru</h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body row g-3">
            <div class="col-12">
                <label class="form-label text-dark-50 small">Nomor Semester</label>
                <input type="number" name="number" class="form-control bg-light text-dark border-secondary" placeholder="1" required>
            </div>
            <div class="col-12">
                <label class="form-label text-dark-50 small">Nama Semester</label>
                <input type="text" name="name" class="form-control bg-light text-dark border-secondary" placeholder="cth: Semester Ganjil" required>
            </div>
        </div>
        <div class="modal-footer border-secondary">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4">Tambah Semester</button>
        </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>