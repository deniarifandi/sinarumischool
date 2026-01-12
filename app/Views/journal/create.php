<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Add Teaching Journal
            </h6>
        </div>
    </div>

    <div class="card-body">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('journal/store') ?>" method="post" id="journalForm">
            <?= csrf_field() ?>

            <div class="row">

                <!-- DATE -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Date</label>
                    <input
                        type="date"
                        name="date"
                        class="form-control"
                        value="<?= old('date', date('Y-m-d')) ?>"
                        required
                    >
                </div>

                <!-- CLASS -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Select Class --</option>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= old('class_id')==$c['id']?'selected':'' ?>>
                                <?= esc($c['class_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- SUBJECT -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Select Subject --</option>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= old('subject_id')==$s['id']?'selected':'' ?>>
                                <?= esc($s['subject_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- CHAPTER -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Chapter</label>
                    <select name="chapter_id" class="form-select">
                        <option value="">-- Select Chapter --</option>
                        <!-- Filled dynamically (optional) -->
                    </select>
                </div>

                <!-- SUB-CHAPTER -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Sub-Chapter</label>
                    <select name="subchapter_id" class="form-select">
                        <option value="">-- Select Sub-Chapter --</option>
                        <!-- Filled dynamically (optional) -->
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Teaching Hours Used</label>
                    <input
                        type="text"
                        name="jpspend"
                        class="form-control"
                        required
                    >
                </div>

                <!-- ACTIVITIES -->
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Teaching Activities</label>
                    <textarea
                        name="activities"
                        class="form-control"
                        rows="4"
                        required
                    ><?= old('activities') ?></textarea>
                </div>

                <!-- NOTES -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Notes</label>
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="3"
                    ><?= old('notes') ?></textarea>
                </div>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('journal') ?>" class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    Save Journal
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
/* Prevent double submit */
$('#journalForm').on('submit', function () {
    $('#btnSubmit').prop('disabled', true).text('Saving...');
});
</script>

<script>
$(function () {

    // Subject → Chapter
    $('select[name="subject_id"]').on('change', function () {
        const subjectId = $(this).val();

        $('select[name="chapter_id"]').html('<option value="">Loading...</option>');
        $('select[name="subchapter_id"]').html('<option value="">-- Select Sub-Chapter --</option>');

        if (!subjectId) return;

        $.get("<?= base_url('journal/get-chapters') ?>", { subject_id: subjectId }, function (data) {
            let options = '<option value="">-- Select Chapter --</option>';
            data.forEach(row => {
                options += `<option value="${row.id}">${row.chapter_name}</option>`;
            });
            $('select[name="chapter_id"]').html(options);
        });
    });

    // Chapter → Sub-Chapter
    $('select[name="chapter_id"]').on('change', function () {
        const chapterId = $(this).val();

        $('select[name="subchapter_id"]').html('<option value="">Loading...</option>');

        if (!chapterId) return;

        $.get("<?= base_url('journal/get-subchapters') ?>", { chapter_id: chapterId }, function (data) {
            let options = '<option value="">-- Select Sub-Chapter --</option>';
            data.forEach(row => {
                options += `<option value="${row.id}">${row.sub_name}</option>`;
            });
            $('select[name="subchapter_id"]').html(options);
        });
    });

});
</script>


<?= $this->endSection() ?>
