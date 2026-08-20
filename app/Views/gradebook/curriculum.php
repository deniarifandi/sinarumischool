<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="container-fluid px-0 py-3">

    <!-- HEADER -->
    <div class="glass-card p-4 mb-4">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold text-light mb-2">
                    <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>
                    Curriculum Gradebook
                </h4>

                <div class="d-flex flex-wrap gap-2">

                    <span class="badge bg-primary">
                        <i class="bi bi-people-fill me-1"></i>
                        <?= esc($class['class_name']) ?>
                    </span>

                    <span class="badge bg-secondary">
                        <i class="bi bi-mortarboard-fill me-1"></i>
                        <?= esc($class['grade_name']) ?>
                    </span>

                    <span class="badge bg-info">
                        <?= esc($term['name']) ?>
                    </span>

                    <span class="badge bg-dark border">
                        <?= esc($academicYear['name']) ?>
                    </span>

                </div>
            </div>

            <div>
                <button
                    type="button"
                    onclick="window.close();"
                    class="btn btn-outline-light rounded-pill px-4">
                    <i class="bi bi-x-lg me-1"></i>
                    Close
                </button>

                 <button type="button" onclick="exportGradebookToExcel()" class="btn btn-outline-success rounded-pill px-4">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </button>
            </div>

        </div>

        <br>

        <button type="button" onclick="toggleStudentNames()" id="toggleNameBtn" class="btn btn-outline-warning rounded-pill px-4">
            <i class="bi bi-eye-slash me-1"></i> Hide Names
        </button>

    </div>


    <!-- SUBJECT CARDS -->

    <?php if (empty($subjects)): ?>

        <div class="alert alert-warning">
            No subjects found for this division.
        </div>

    <?php else: ?>

        <?php foreach ($subjects as $subjectData): ?>

            <?php
            // =====================================================
            // SUBJECT DATA
            // =====================================================

            $subject = $subjectData['subject'];
            $scores  = $subjectData['scores'];

            $subjectName = trim($subject['subject_name'] ?? '');


            // =====================================================
            // RELIGION MAPPING
            // =====================================================

            $religionMap = [
                'islam'     => 'islam',

                'christian' => 'christian',
                'kristen'   => 'christian',

                'catholic'  => 'catholic',
                'katolik'   => 'catholic',

                'buddhist'  => 'buddhist',
                'buddha'    => 'buddhist',
                'budha'     => 'buddhist',

                'hindu'     => 'hindu',
            ];


            // =====================================================
            // DETECT RELIGION SUBJECT
            // =====================================================

            $isReligionSubject = false;
            $subjectReligion   = null;

            if (
                preg_match(
                    '/^Religion\s*:\s*(.+)$/i',
                    $subjectName,
                    $matches
                )
            ) {

                $isReligionSubject = true;

                $subjectReligion = strtolower(
                    trim($matches[1])
                );

                $subjectReligion =
                    $religionMap[$subjectReligion] ?? null;
            }
            ?>

            <div class="glass-card p-3 mb-4">

                <!-- SUBJECT HEADER -->

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>

                        <h5 class="fw-bold text-light mb-1">

                            <i class="bi bi-book-half text-warning me-2"></i>

                            <?= esc($subjectName) ?>

                        </h5>

                        <?php if (!empty($subject['subject_code'])): ?>

                            <small class="text-white-50">
                                <?= esc($subject['subject_code']) ?>
                            </small>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- TABLE -->

                <div class="table-responsive">

                    <table class="table table-sm table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th rowspan="2" class="text-center">
                                    No
                                </th>

                                <th rowspan="2" style="min-width:200px;">
                                    Student
                                </th>

                                <th colspan="2" class="text-center">
                                    Chapter Test 1
                                </th>

                                <th colspan="2" class="text-center">
                                    Chapter Test 2
                                </th>

                                <th rowspan="2" class="text-center">
                                    Individual<br>
                                    Project
                                </th>

                                <th rowspan="2" class="text-center">
                                    Group<br>
                                    Project
                                </th>

                            </tr>


                            <tr>

                                <th class="text-center">
                                    CT1
                                </th>

                                <th class="text-center">
                                    Rem.
                                </th>

                                <th class="text-center">
                                    CT2
                                </th>

                                <th class="text-center">
                                    Rem.
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if (empty($students)): ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center text-muted"
                                >
                                    No students found.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach ($students as $index => $student): ?>

                                <?php
                                // =================================================
                                // STUDENT
                                // =================================================

                                $studentId = $student['id'];

                                $score = $scores[$studentId] ?? [];


                                // =================================================
                                // STUDENT RELIGION
                                // =================================================

                                $studentReligion = strtolower(
                                    trim(
                                        $student['murid_agama'] ?? ''
                                    )
                                );

                                $studentReligion =
                                    $religionMap[$studentReligion] ?? null;


                                // =================================================
                                // RELIGION MISMATCH
                                // =================================================

                                $religionMismatch = (
                                    $isReligionSubject &&
                                    $subjectReligion !== null &&
                                    $studentReligion !== $subjectReligion
                                );


                                // Grey row
                                $rowClass = $religionMismatch
                                    ? 'table-secondary text-muted'
                                    : '';
                                ?>

                                <tr class="<?= $rowClass ?>">

                                    <!-- NUMBER -->

                                    <td class="text-center">

                                        <?= $index + 1 ?>

                                    </td>


                                    <!-- STUDENT -->

                                    <td>

                                        <strong>
                                            <?= esc($student['name']) ?>
                                        </strong>

                                    </td>


                                    <!-- CT1 -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct1'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- CT1 REMEDIAL -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct1_remedial'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- CT2 -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct2'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- CT2 REMEDIAL -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['ct2_remedial'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- INDIVIDUAL PROJECT -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['individual_project'] ?? '-'
                                        ) ?>

                                    </td>


                                    <!-- GROUP PROJECT -->

                                    <td class="text-center">

                                        <?= esc(
                                            $score['group_project'] ?? '-'
                                        ) ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

<script>
function exportGradebookToExcel() {
    const wb = XLSX.utils.book_new();

    document.querySelectorAll('.glass-card').forEach(card => {
        const titleEl = card.querySelector('h5');
        const table = card.querySelector('table');

        if (!titleEl || !table) return;

        let sheetName = titleEl.innerText.trim().replace(/[:\\\/\?\*\[\]]/g, '-').substring(0, 31);
        if (!sheetName) sheetName = 'Subject';

        const ws = XLSX.utils.table_to_sheet(table, { raw: false });

        // ---- Auto-fit column widths ----
        const range = XLSX.utils.decode_range(ws['!ref']);
        const colWidths = [];

        for (let col = range.s.c; col <= range.e.c; col++) {
            let maxLen = 5; // minimum width

            for (let row = range.s.r; row <= range.e.r; row++) {
                const cellRef = XLSX.utils.encode_cell({ r: row, c: col });
                const cell = ws[cellRef];

                if (cell && cell.v != null) {
                    const len = String(cell.v).length;
                    if (len > maxLen) maxLen = len;
                }
            }

            colWidths.push({ wch: maxLen + 2 }); // padding
        }

        ws['!cols'] = colWidths;

        XLSX.utils.book_append_sheet(wb, ws, sheetName);
    });

    const className = document.querySelector('.badge.bg-primary')?.innerText.trim() || 'Class';
    const termName  = document.querySelector('.badge.bg-info')?.innerText.trim() || 'Term';

    XLSX.writeFile(wb, `Gradebook_${className}_${termName}.xlsx`.replace(/\s+/g, '_'));
}
</script>

<script>
const STORAGE_KEY = 'gradebook_names_hidden';
let namesHidden = localStorage.getItem(STORAGE_KEY) === 'true';

function applyNameVisibility() {
    document.querySelectorAll('table tbody tr').forEach(row => {
        const nameCell = row.children[1]; // 2nd column = Student
        if (!nameCell) return;

        const strongEl = nameCell.querySelector('strong');
        if (!strongEl) return;

        if (!strongEl.dataset.originalName) {
            strongEl.dataset.originalName = strongEl.innerText;
        }

        strongEl.innerText = namesHidden ? '••••••••' : strongEl.dataset.originalName;
    });

    const btn = document.getElementById('toggleNameBtn');
    if (btn) {
        btn.innerHTML = namesHidden
            ? '<i class="bi bi-eye me-1"></i> Show Names'
            : '<i class="bi bi-eye-slash me-1"></i> Hide Names';
    }
}

function toggleStudentNames() {
    namesHidden = !namesHidden;
    localStorage.setItem(STORAGE_KEY, namesHidden);
    applyNameVisibility();
}

// Run on page load, before user clicks anything
document.addEventListener('DOMContentLoaded', applyNameVisibility);
</script>

<?= $this->endSection() ?>
