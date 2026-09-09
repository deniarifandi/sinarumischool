<?php
/**
 * Expected vars: $isLocked, $isReligionSubject, $subjectReligion
 * Validation errors are read directly from flashdata.
 */
?>
<?php
$isLocked          = $isLocked          ?? false;
$isReligionSubject = $isReligionSubject ?? false;
$subjectReligion   = $subjectReligion   ?? null;
?>

<?php if (session()->getFlashdata('validation_errors')): ?>

    <div class="alert alert-danger py-2 mb-2 small">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        <strong>Nilai tidak valid, tidak ada yang tersimpan:</strong>

        <ul class="mb-0 mt-1">
            <?php foreach (session()->getFlashdata('validation_errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>


<?php if (isset($isLocked) && $isLocked): ?>

    <div class="alert alert-warning py-1 mb-2 small">
        <i class="bi bi-lock-fill me-1"></i>
        <strong>Locked</strong> - Scores can no longer be edited.
    </div>

<?php else: ?>

    <div class="alert alert-info py-1 mb-2 small">
        <i class="bi bi-info-circle me-1"></i>
        Paste directly from Excel into the first cell using <strong>Ctrl + V</strong>.
    </div>

<?php endif; ?>


<?php if (isset($isReligionSubject) && $isReligionSubject): ?>

    <div class="alert alert-secondary py-1 mb-2 small">
        <i class="bi bi-info-circle me-1"></i>
        This is a religion subject: <strong><?= esc($subjectReligion ?? 'Unknown') ?></strong>.
        Students from other religions are shown in grey and cannot be edited.
    </div>

<?php endif; ?>
