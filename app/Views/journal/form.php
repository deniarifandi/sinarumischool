<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
    $isEdit    = ($mode ?? 'create') === 'edit';
    $action    = $isEdit ? base_url('journal/update/'.$journal['id']) : base_url('journal/store');
    $oldDate   = old('date',         $journal['date']        ?? date('Y-m-d'));
    $oldClass  = old('class_id',     $journal['class_id']    ?? '');
    $oldGrade  = old('grade_id',     $selectedGrade          ?? '');
    $oldUnit   = old('unit_id',      $journal['unit_id']     ?? '');
    $oldSubunit= old('subunit_id',   $journal['subunit_id']  ?? '');
    $oldPeriod = old('periods',      $journal['periods']     ?? 2);
    $oldAct    = old('activities',   $journal['activities']  ?? '');
    $oldNotes  = old('notes',        $journal['notes']       ?? '');
    $subjectName = $subject['subject_name'] ?? '—';
?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= $isEdit ? 'Edit Teaching Journal' : 'Add Teaching Journal' ?>
    </h5>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= $action ?>" method="post" id="journalForm">
        <?= csrf_field() ?>

        <!-- Subject (locked when reached from the dashboard) -->
        <div class="mb-3">
            <label class="form-label">Subject</label>
            <div class="form-control bg-light-subtle">
                <i class="bi bi-bookmark-fill text-danger me-1"></i>
                <strong><?= esc($subjectName) ?></strong>
            </div>
            <input type="hidden" name="subject_id" value="<?= esc($subjectId) ?>">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control"
                       value="<?= esc($oldDate) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">JP</label>
                <input type="number" name="periods" class="form-control"
                       min="1" max="12" value="<?= esc($oldPeriod) ?>"
                       placeholder="Jam Pelajaran">
            </div>
        </div>

        <!-- Grade picker (chips) -->
        <div class="mb-3">
            <label class="form-label">Grade <span class="text-danger">*</span></label>
            <div class="grade-picker" id="gradePicker">
                <?php if (empty($grades)): ?>
                    <span class="text-muted small">No grades available.</span>
                <?php endif; ?>
                <?php foreach ($grades as $g): ?>
                    <label class="grade-chip" data-grade-id="<?= (int) $g['id'] ?>">
                        <input type="radio" name="grade_id"
                               value="<?= (int) $g['id'] ?>"
                               <?= (string)$oldGrade === (string)$g['id'] ? 'checked' : '' ?>
                               required>
                        <span class="grade-chip-box">
                            <i class="bi bi-mortarboard-fill"></i>
                            <span class="grade-chip-label"><?= esc($g['grade_name']) ?></span>
                        </span>
                    </label>
                <?php endforeach ?>
            </div>
        </div>

        <!-- Class picker (cards) -->
        <div class="mb-3">
            <label class="form-label">
                Class <span class="text-danger">*</span>
                <small class="text-muted">(pilih grade dulu)</small>
            </label>
            <div class="class-picker" id="classPicker">
                <span class="text-muted small">Pilih grade terlebih dahulu untuk memuat kelas.</span>
            </div>
        </div>

        <!-- Unit picker (dropdown) -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Unit
                    <small class="text-muted">(pilih kelas dulu)</small>
                </label>
                <select name="unit_id" id="unitSelect" class="form-control" disabled>
                    <option value="">-- No unit --</option>
                    <?php foreach ($units as $u): ?>
                        <option value="<?= (int) $u['id'] ?>"
                            <?= (string)$oldUnit === (string)$u['id'] ? 'selected' : '' ?>>
                            <?= esc($u['name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">
                    Sub-Unit
                    <small class="text-muted">(pilih unit dulu)</small>
                </label>
                <select name="subunit_id" id="subunitSelect" class="form-control" disabled>
                    <option value="">-- None --</option>
                    <?php foreach ($subunits as $su): ?>
                        <option value="<?= (int) $su['id'] ?>"
                            <?= (string)$oldSubunit === (string)$su['id'] ? 'selected' : '' ?>>
                            <?= esc($su['subunit_name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Activities</label>
            <textarea name="activities" class="form-control" rows="4"
                      placeholder="Ringkasan kegiatan pembelajaran, metode, langkah-langkah"><?= esc($oldAct) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes / Refleksi</label>
            <textarea name="notes" class="form-control" rows="3"
                      placeholder="Catatan, hambatan, tindak lanjut"><?= esc($oldNotes) ?></textarea>
        </div>

        <input type="hidden" name="teacher_id" value="<?= esc($teacherId) ?>">

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('journal') ?>"
               class="btn btn-outline-secondary me-2">Back</a>
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Update' : 'Save' ?>
            </button>
        </div>
    </form>
</div>

<style>
.grade-picker {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.grade-chip { cursor: pointer; }
.grade-chip input { display: none; }
.grade-chip-box {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    border: 2px solid rgba(0,0,0,0.08);
    border-radius: 999px;
    background: #f7f7f9;
    font-weight: 600;
    color: #444;
    transition: all 0.15s ease;
}
.grade-chip-box i { color: #888; }
.grade-chip:hover .grade-chip-box {
    border-color: #6c63ff;
    color: #6c63ff;
}
.grade-chip input:checked + .grade-chip-box {
    background: #6c63ff;
    color: #fff;
    border-color: #6c63ff;
    box-shadow: 0 4px 14px rgba(108,99,255,0.4);
}
.grade-chip input:checked + .grade-chip-box i { color: #fff; }

.class-picker {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 0.75rem;
}
.class-card { cursor: pointer; }
.class-card input { display: none; }
.class-card-inner {
    border: 2px solid rgba(0,0,0,0.08);
    border-radius: 14px;
    padding: 0.9rem 0.5rem;
    text-align: center;
    background: #fff;
    transition: all 0.15s ease;
    min-height: 90px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.class-card-inner i {
    font-size: 1.6rem;
    color: #aaa;
    margin-bottom: 0.3rem;
}
.class-card-name { font-weight: 700; color: #222; font-size: 1.05rem; }
.class-card-grade { font-size: 0.75rem; color: #888; }
.class-card:hover .class-card-inner {
    border-color: #6c63ff;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(108,99,255,0.15);
}
.class-card input:checked + .class-card-inner {
    border-color: #6c63ff;
    background: linear-gradient(135deg, #6c63ff 0%, #8b5cf6 100%);
    color: #fff;
    box-shadow: 0 6px 18px rgba(108,99,255,0.45);
}
.class-card input:checked + .class-card-inner i,
.class-card input:checked + .class-card-inner .class-card-grade { color: rgba(255,255,255,0.9); }
.class-card input:checked + .class-card-inner .class-card-name { color: #fff; }

.class-card.hidden { display: none; }
.class-picker .text-muted { padding: 1rem 0; }

.form-control:disabled,
.form-control[disabled] {
    background-color: #e9ecef !important;
    color: #6c757d !important;
    cursor: not-allowed;
    opacity: 0.65;
}
</style>

<script>
(function () {
    const gradeRadios   = document.querySelectorAll('input[name="grade_id"]');
    const classPicker   = document.getElementById('classPicker');
    const unitSelect    = document.getElementById('unitSelect');
    const subunitSelect = document.getElementById('subunitSelect');
    const subjectId     = <?= (int) $subjectId ?>;
    const initialClass  = <?= (int) ($oldClass ?: 0) ?>;
    const initialUnit   = <?= (int) ($oldUnit  ?: 0) ?>;

    function renderClassCards(classes) {
        if (!classes || classes.length === 0) {
            classPicker.innerHTML = '<span class="text-muted small">Tidak ada kelas untuk grade ini.</span>';
            return;
        }
        let html = '';
        classes.forEach(c => {
            const checked = String(initialClass) === String(c.id) ? 'checked' : '';
            html += `
                <label class="class-card">
                    <input type="radio" name="class_id" value="${c.id}" ${checked} required>
                    <div class="class-card-inner">
                        <i class="bi bi-people-fill"></i>
                        <div class="class-card-name">${escapeHtml(c.class_name)}</div>
                        <div class="class-card-grade">${escapeHtml(c.grade_name || '')}</div>
                    </div>
                </label>
            `;
        });
        classPicker.innerHTML = html;

        // Re-bind class change listeners after render
        classPicker.querySelectorAll('input[name="class_id"]').forEach(r => {
            r.addEventListener('change', onClassChange);
        });
    }

    function setUnitEnabled(enabled) {
        unitSelect.disabled = ! enabled;
        if (! enabled) {
            unitSelect.value = '';
        }
    }

    function setSubunitEnabled(enabled) {
        subunitSelect.disabled = ! enabled;
        if (! enabled) {
            subunitSelect.value = '';
        }
    }

    function onClassChange() {
        const selectedGrade = document.querySelector('input[name="grade_id"]:checked');
        if (selectedGrade) {
            loadUnits(selectedGrade.value);
            setUnitEnabled(true);
        }
        setSubunitEnabled(false);
    }

    gradeRadios.forEach(r => {
        r.addEventListener('change', e => {
            // Clear class selection
            classPicker.querySelectorAll('input[name="class_id"]:checked').forEach(sel => sel.checked = false);
            // Reset cascade
            setSubunitEnabled(false);
            setUnitEnabled(false);
            // Load classes for this grade
            loadClasses(e.target.value);
        });
    });

    function loadClasses(gradeId) {
        if (! gradeId) {
            classPicker.innerHTML = '<span class="text-muted small">Pilih grade terlebih dahulu.</span>';
            return;
        }
        classPicker.innerHTML = '<span class="text-muted small">Memuat kelas…</span>';
        const url = `<?= base_url('journal/classes') ?>?subject_id=${subjectId}&grade_id=${gradeId}`;
        fetch(url)
            .then(r => r.json())
            .then(data => renderClassCards(data.classes || []));
    }

    function loadUnits(gradeId) {
        if (! subjectId || ! gradeId) {
            unitSelect.innerHTML = '<option value="">-- No unit --</option>';
            return;
        }
        unitSelect.innerHTML = '<option value="">Memuat...</option>';
        fetch(`<?= base_url('journal/units') ?>?subject_id=${subjectId}&grade_id=${gradeId}`)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">-- No unit --</option>';
                (data.units || []).forEach(u => {
                    const sel = String(initialUnit) === String(u.id) ? 'selected' : '';
                    html += `<option value="${u.id}" ${sel}>${escapeHtml(u.name)}</option>`;
                });
                unitSelect.innerHTML = html;
            });
    }

    function loadSubunits(unitId) {
        if (! unitId) {
            subunitSelect.innerHTML = '<option value="">-- None --</option>';
            return;
        }
        subunitSelect.innerHTML = '<option value="">Memuat...</option>';
        fetch(`<?= base_url('journal/subunits') ?>?unit_id=${unitId}`)
            .then(r => r.json())
            .then(data => {
                let html = '<option value="">-- None --</option>';
                const initialSub = <?= (int) ($oldSubunit ?: 0) ?>;
                (data.subunits || []).forEach(s => {
                    const sel = String(initialSub) === String(s.id) ? 'selected' : '';
                    html += `<option value="${s.id}" ${sel}>${escapeHtml(s.subunit_name)}</option>`;
                });
                subunitSelect.innerHTML = html;
            });
    }

    unitSelect.addEventListener('change', e => {
        if (e.target.value) {
            loadSubunits(e.target.value);
            setSubunitEnabled(true);
        } else {
            setSubunitEnabled(false);
        }
    });

    // Initialise on load (for edit mode or after validation error).
    (function initFromState() {
        const selectedGrade = document.querySelector('input[name="grade_id"]:checked');
        if (selectedGrade) {
            loadClasses(selectedGrade.value);
            if (initialClass) {
                setUnitEnabled(true);
                loadUnits(selectedGrade.value);
                if (initialUnit) {
                    setSubunitEnabled(true);
                    loadSubunits(initialUnit);
                }
            }
        }
    })();

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, c => ({
            '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
        }[c]));
    }
})();
</script>

<?= $this->endSection() ?>
