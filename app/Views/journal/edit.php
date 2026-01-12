<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3">
            <h6 class="text-white ps-3 mb-0">
                Edit Teaching Journal
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

        <form action="<?= base_url('journal/update/'.$journal['id']) ?>"
              method="post"
              id="journalForm">

            <?= csrf_field() ?>

            <div class="row">

                <!-- DATE -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date"
                           name="date"
                           class="form-control"
                           value="<?= old('date', $journal['date']) ?>"
                           required>
                </div>

                <!-- CLASS -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Class</label>
                    <select name="class_id" class="form-select" required>
                        <?php foreach ($classes as $c): ?>
                            <option value="<?= $c['id'] ?>"
                                <?= $journal['class_id']==$c['id']?'selected':'' ?>>
                                <?= esc($c['class_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- SUBJECT -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Subject</label>
                    <select name="subject_id"
                            id="subject_id"
                            class="form-select"
                            required>
                        <?php foreach ($subjects as $s): ?>
                            <option value="<?= $s['id'] ?>"
                                <?= $journal['subject_id']==$s['id']?'selected':'' ?>>
                                <?= esc($s['subject_name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <!-- CHAPTER -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Chapter</label>
                    <select name="chapter_id"
                            id="chapter_id"
                            class="form-select">
                        <option value="">-- Select Chapter --</option>
                    </select>
                </div>

                <!-- SUBCHAPTER -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Sub-Chapter</label>
                    <select name="subchapter_id"
                            id="subchapter_id"
                            class="form-select">
                        <option value="">-- Select Sub-Chapter --</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Teaching Hours Used</label>
                    <input
                        type="text"
                        name="jpspend"
                        class="form-control"
                          value="<?= old('jpspend', $journal['jpspend']) ?>"
                        required
                    >
                </div>

                <!-- ACTIVITIES -->
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Teaching Activities</label>
                    <textarea name="activities"
                              class="form-control"
                              rows="4"
                              required><?= old('activities', $journal['activities']) ?></textarea>
                </div>

                <!-- NOTES -->
                <div class="col-12 mb-4">
                    <label class="form-label fw-bold">Notes</label>
                    <textarea name="notes"
                              class="form-control"
                              rows="3"><?= old('notes', $journal['notes']) ?></textarea>
                </div>

            </div>

            <!-- ACTIONS -->
            <div class="d-flex justify-content-end gap-2">
                <a href="<?= base_url('journal') ?>"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
                <button type="submit"
                        class="btn btn-primary"
                        id="btnSubmit">
                    Update Journal
                </button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
$(function () {

    const selectedChapter    = "<?= $journal['chapter_id'] ?>";
    const selectedSubchapter = "<?= $journal['subchapter_id'] ?>";

    function loadChapters(subjectId, selected = null) {
        $('#chapter_id').html('<option>Loading...</option>');
        $('#subchapter_id').html('<option>-- Select Sub-Chapter --</option>');

        if (!subjectId) return;

        $.get("<?= base_url('journal/get-chapters') ?>",
            { subject_id: subjectId },
            function (data) {
                let opt = '<option value="">-- Select Chapter --</option>';
                data.forEach(r => {
                    const sel = r.id == selected ? 'selected' : '';
                    opt += `<option value="${r.id}" ${sel}>${r.chapter_name}</option>`;
                });
                $('#chapter_id').html(opt);

                if (selected) {
                    loadSubchapters(selected, selectedSubchapter);
                }
            }
        );
    }

    function loadSubchapters(chapterId, selected = null) {
        $('#subchapter_id').html('<option>Loading...</option>');

        if (!chapterId) return;

        $.get("<?= base_url('journal/get-subchapters') ?>",
            { chapter_id: chapterId },
            function (data) {
                let opt = '<option value="">-- Select Sub-Chapter --</option>';
                data.forEach(r => {
                    const sel = r.id == selected ? 'selected' : '';
                    opt += `<option value="${r.id}" ${sel}>${r.sub_name}</option>`;
                });
                $('#subchapter_id').html(opt);
            }
        );
    }

    // INIT
    const subjectId = $('#subject_id').val();
    if (subjectId) {
        loadChapters(subjectId, selectedChapter);
    }

    // EVENTS
    $('#subject_id').on('change', function () {
        loadChapters(this.value);
    });

    $('#chapter_id').on('change', function () {
        loadSubchapters(this.value);
    });

    // Prevent double submit
    $('#journalForm').on('submit', function () {
        $('#btnSubmit').prop('disabled', true).text('Updating...');
    });

});
</script>

<?= $this->endSection() ?>
