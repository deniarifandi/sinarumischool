<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card shadow-sm border-0 p-4 p-md-5">
                
                <header class="mb-4">
                    <h3 class="fw-bold text-primary">
                        <?= isset($objective) ? 'Edit Objective' : 'Create New Objective' ?>
                    </h3>
                    <p class="text-muted small">Fill in the details below to define your learning objective.</p>
                </header>

                <form action="<?= isset($objective) 
                    ? base_url('objective/update/'.$objective['id']) 
                    : base_url('objective/store') ?>" 
                    method="post" autocomplete="off">

                    <?= csrf_field() ?>

                    <input type="hidden" name="outcome_id" value="<?= esc($outcome_id) ?>">

                    <div class="mb-4">
                        <label for="objective_name" class="form-label fw-semibold">Objective Name</label>
                        <input type="text" 
                               id="objective_name"
                               name="objective_name" 
                               class="form-control form-control-lg <?= session('errors.objective_name') ? 'is-invalid' : '' ?>" 
                               placeholder="e.g. Understand the fundamentals of UI design"
                               required 
                               value="<?= old('objective_name', $objective['objective_name'] ?? '') ?>">
                        
                        <?php if (session('errors.objective_name')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.objective_name') ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form-text">Keep names concise and action-oriented.</div>
                    </div>

                    <hr class="my-4 opacity-50">

                    <div class="d-flex align-items-center justify-content-between">
                        <a href="<?= base_url('objective?subject_id='.($subjectId ?? $outcome['subject_id'] ?? '')) ?>" 
                           class="btn btn-link text-decoration-none text-secondary p-0">
                           <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>

                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i>
                            <?= isset($objective) ? 'Update Objective' : 'Save Objective' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>