<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4">
        <?= isset($outcome) ? 'Edit Outcome' : 'Add Outcome' ?>
            <?php $subjectId = esc($subject_id ?? $_GET['subject_id'] ?? '-') ?>
    </h5>

    <form action="<?= isset($outcome)
        ? base_url('outcome/update/'.$outcome['id'])
        : base_url('outcome/store') ?>"
        method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="subject_id"
               value="<?php echo $subjectId ?>">

    <?php 
$selectedGrade = old('grade_id') 
    ?? ($outcome['grade_id'] ?? ($_GET['grade'] ?? ''));
?>

        <div class="mb-3">
            <label class="form-label">Grade</label>
            <select name="grade_id" id="gradeSelect" class="form-select" required>
                <option value="">-- Choose Grade --</option>
                <?php foreach ($grades as $g): ?>
                    <option value="<?= $g['id'] ?>" <?= (isset($outcome) && $outcome['grade_id'] == $g['id']) ? 'selected' : '' ?>>
                        <?= esc($g['grade_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- =========================================================
             REFERENCE FROM UNIT -> SUBUNIT (optional)
             When a subunit is chosen, it fills the Outcome Name below.
             ========================================================= -->
        <div class="mb-4 p-3 rounded-3" style="border:1px dashed rgba(255,255,255,.15);">
            <div class="text-white-50 small mb-2">
                <i class="bi bi-link-45deg me-1"></i>
                Atau ambil dari Unit & Sub-Unit (akan mengisi nama outcome di bawah):
            </div>

            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small text-white-50">Unit</label>
                    <select id="unitSelect" class="form-select form-select-sm">
                        <option value="">-- Choose Unit --</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label small text-white-50">Sub-Unit</label>
                    <select id="subunitSelect" class="form-select form-select-sm" disabled>
                        <option value="">-- Choose Sub-Unit --</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" id="useSubunitBtn" class="btn btn-sm btn-outline-light w-100" disabled>
                        Use
                    </button>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Outcome Name</label>
            <input type="text"
                   name="outcome_name"
                   id="outcomeName"
                   class="form-control"
                   required
                   value="<?= old('outcome_name', $outcome['outcome_name'] ?? '') ?>">
        </div>

        <div class="d-flex justify-content-end">
            <a href="<?= base_url('outcome?subject_id='.($subjectId ?? $outcome['subject_id'] ?? '')) ?>"
               class="btn btn-outline-secondary me-2">
                Back
            </a>

            <button type="submit"
                    class="btn btn-primary">
                Save
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function () {

    const subjectId = <?= (int)($subject_id ?? $_GET['subject_id'] ?? 0) ?>;

    // ---- Load units when a grade is picked ----
    function loadUnits() {
        const gradeId = $('#gradeSelect').val();

        $('#unitSelect').html('<option value="">-- Choose Unit --</option>');
        $('#subunitSelect').html('<option value="">-- Choose Sub-Unit --</option>')
            .prop('disabled', true);
        $('#useSubunitBtn').prop('disabled', true);

        if (!gradeId) return;

        $.get('<?= base_url('outcome/units') ?>', { subject_id: subjectId, grade_id: gradeId })
            .done(function (res) {
                if (res.units && res.units.length) {
                    $.each(res.units, function (_, u) {
                        $('#unitSelect').append(
                            $('<option>', { value: u.id, text: u.name })
                        );
                    });
                }
            });
    }

    // ---- Load subunits when a unit is picked ----
    $('#unitSelect').on('change', function () {
        const unitId = $(this).val();

        $('#subunitSelect').html('<option value="">-- Choose Sub-Unit --</option>');
        $('#useSubunitBtn').prop('disabled', true);

        if (!unitId) {
            $('#subunitSelect').prop('disabled', true);
            return;
        }

        $('#subunitSelect').prop('disabled', false);

        $.get('<?= base_url('outcome/subunits') ?>', { unit_id: unitId })
            .done(function (res) {
                if (res.subunits && res.subunits.length) {
                    $.each(res.subunits, function (_, su) {
                        $('#subunitSelect').append(
                            $('<option>', { value: su.id, text: su.subunit_name })
                        );
                    });
                }
            });
    });

    // ---- Use selected subunit as outcome name ----
    $('#useSubunitBtn').on('click', function () {
        const name = $('#subunitSelect option:selected').text();
        if (name && name !== '-- Choose Sub-Unit --') {
            $('#outcomeName').val(name).trigger('input');
        }
    });

    $('#gradeSelect').on('change', loadUnits);
    $('#subunitSelect').on('change', function () {
        $('#useSubunitBtn').prop('disabled', !this.value);
    });

    // On edit, pre-load units for the selected grade if there's an outcome.
    <?php if (isset($outcome) && !empty($outcome['grade_id'])): ?>
    loadUnits();
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
