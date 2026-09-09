<?php
/**
 * Expected vars: $subject, $class, $term, $semester, $academicYear
 */
?>
<?php
$subject       = $subject       ?? [];
$class         = $class         ?? [];
$term          = $term          ?? [];
$semester      = $semester      ?? [];
$academicYear  = $academicYear  ?? [];
?>

<div
    class="d-flex justify-content-between align-items-center mb-3 pb-3"
    style="border-bottom: 1px solid rgba(255,255,255,0.08);"
>

    <div class="neon-accent">

        <h5
            class="m-2 mt-3 mx-2 fw-bold neon-title"
            style="margin-right: 10px;"
        >
            <i class="bi bi-journal-check me-2"></i>
            Gradebook - <?= esc($subject['subject_name'] ?? '-') ?>
        </h5>

        <div class="d-flex flex-wrap mt-1 mb-3" style="margin-right: 10px">

            <span class="neon-badge">
                <i class="bi bi-people-fill me-1"></i>
                <?= esc($class['class_name'] ?? '-') ?>
            </span>

            <span class="neon-badge">
                <i class="bi bi-bookmark-fill me-1"></i>
                <?= esc($term['name'] ?? '-') ?>
            </span>

            <span class="neon-badge">
                <i class="bi bi-calendar3 me-1"></i>
                <?= esc($semester['name'] ?? '-') ?>
            </span>

            <span class="neon-badge">
                <i class="bi bi-clock-history me-1"></i>
                <?= esc($academicYear['name'] ?? '-') ?>
            </span>

        </div>

    </div>

    <div>
        <button
            type="button"
            onclick="window.close();"
            id="closeBtn"
            class="btn btn-outline-light rounded-pill px-4 py-2 shadow-sm"
            style="border-color: rgba(255,255,255,0.3);"
        >
            <i class="bi bi-x-lg me-1"></i>
            Close Tab
        </button>
    </div>

</div>
