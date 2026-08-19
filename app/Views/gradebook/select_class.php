<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php
$groupedClasses = [];
foreach ($classes as $class) {
    $groupedClasses[$class['grade_name']][] = $class;
}

$gradeThemes = [
    ['icon' => 'bi-mortarboard-fill', 'color' => '#3b82f6', 'glow' => 'rgba(59, 130, 246, 0.35)'],
    ['icon' => 'bi-book-half', 'color' => '#22c55e', 'glow' => 'rgba(34, 197, 94, 0.35)'],
    ['icon' => 'bi-journal-bookmark-fill', 'color' => '#a855f7', 'glow' => 'rgba(168, 85, 247, 0.35)'],
    ['icon' => 'bi-easel2-fill', 'color' => '#f43f5e', 'glow' => 'rgba(244, 63, 94, 0.35)'],
    ['icon' => 'bi-palette-fill', 'color' => '#f97316', 'glow' => 'rgba(249, 115, 22, 0.35)'],
    ['icon' => 'bi-globe2', 'color' => '#14b8a6', 'glow' => 'rgba(20, 184, 166, 0.35)']
];

// $subjectDetailc dikirim dari controller sebagai array of stdClass (hasil ->get()->getResult()).
// Ambil elemen pertama dengan aman — kalau kosong, tampilkan fallback daripada error.
$subjectName = $subjectDetailc[0]->subject_name ?? '-';
?>

<style>
    /* ============ Subject Highlight ============ */
    .subject-highlight {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.02));
        border: 1px solid rgba(245, 158, 11, 0.4);
        border-radius: 12px;
        padding: 12px 20px 12px 24px;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.15);
        position: relative;
        overflow: hidden;
        min-width: 250px;
    }
    .subject-highlight::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 5px; height: 100%;
        background: #f59e0b; /* Amber */
        box-shadow: 0 0 10px #f59e0b;
    }
    .subject-highlight .label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(255, 255, 255, 0.65);
        font-weight: 700;
        margin-bottom: 4px;
    }
    .subject-highlight .value {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fbbf24; /* Bright Amber/Gold */
        letter-spacing: 0.02em;
        text-shadow: 0 0 15px rgba(251, 191, 36, 0.5);
        display: flex;
        align-items: center;
    }

    /* ============ Filter Section ============ */
    .period-selector {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    .period-selector::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    }

    .custom-select-dark {
        background-color: rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
        border-radius: 10px;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    .custom-select-dark:focus {
        background-color: rgba(0, 0, 0, 0.4);
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .custom-select-dark:disabled { opacity: 0.5; cursor: not-allowed; }
    .custom-select-dark option { background-color: #1e293b; color: #ffffff; }

    /* ============ Grade group heading ============ */
    .grade-group { margin-bottom: 2rem; }
    .grade-group-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1rem;
        padding-bottom: 0.6rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .grade-group-title .dot {
        width: 8px; height: 8px; border-radius: 50%;
        box-shadow: 0 0 8px currentColor;
    }
    .grade-group-title h6 { margin: 0; font-weight: 700; letter-spacing: 0.02em; }
    .grade-group-title .count-badge {
        margin-left: auto;
        font-size: 0.72rem;
        font-weight: 600;
        color: rgba(255,255,255,0.4);
    }

    /* ============ Modern class cards ============ */
    .class-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 14px;
    }

    .class-card {
        --accent: #3b82f6;
        --glow: rgba(59, 130, 246, 0.35);

        position: relative;
        display: flex;
        flex-direction: column;
        gap: 14px;
        padding: 18px;
        border-radius: 18px;
        text-decoration: none;
        background: linear-gradient(160deg, rgba(255,255,255,0.05), rgba(255,255,255,0.015));
        border: 1px solid rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(6px);
        transition: transform 0.28s cubic-bezier(0.4,0,0.2,1), border-color 0.28s ease, box-shadow 0.28s ease, background 0.28s ease;
        overflow: hidden;
    }
    .class-card::after {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 90px; height: 90px;
        background: var(--glow);
        filter: blur(28px);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }
    .class-card:hover {
        transform: translateY(-5px);
        border-color: var(--accent);
        background: linear-gradient(160deg, rgba(255,255,255,0.09), rgba(255,255,255,0.02));
        box-shadow: 0 14px 28px -10px var(--glow);
    }
    .class-card:hover::after { opacity: 1; }

    .class-card .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .class-card .icon-badge {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px;
        background: color-mix(in srgb, var(--accent) 18%, transparent);
        color: var(--accent);
        font-size: 1.1rem;
        transition: transform 0.3s ease;
    }
    .class-card:hover .icon-badge { transform: scale(1.08) rotate(-4deg); }

    .class-card .arrow-btn {
        width: 28px; height: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.4);
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .class-card:hover .arrow-btn {
        background: var(--accent);
        color: #fff;
        transform: translateX(2px);
    }

    .class-card .class-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #f1f5f9;
        letter-spacing: -0.01em;
        line-height: 1.15;
    }
    .class-card .class-caption {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.35);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-weight: 600;
    }

    /* ============ Empty state ============ */
    .empty-state {
        border: 1.5px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 3rem 1.5rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.45);
    }
    .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; opacity: 0.6; }

    .grade-group.grade-hidden { display: none; }
</style>

<div class="container-fluid px-0 py-3">

    <!-- Header Section (Now with High Contrast Subject Badge) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h5 class="mb-2 fw-bold text-light d-flex align-items-center">
                <i class="bi bi-grid-fill me-2 text-primary"></i> Pilih Kelas
            </h5>
            <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                Tentukan tahun ajaran dan term terlebih dahulu untuk mengakses kelas.
            </p>
        </div>
        
        <!-- HIGH CONTRAST SUBJECT BADGE -->
        <div class="subject-highlight">
            <div class="label">Mengelola Nilai Mata Pelajaran</div>
            <div class="value">
                <i class="bi bi-journal-bookmark-fill me-2 opacity-75"></i> 
                <?= esc($subjectName) ?>
            </div>
        </div>
    </div>

    <!-- PERIOD SELECTOR -->
    <div class="period-selector p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h6 class="text-light mb-0 fw-bold">
                <i class="bi bi-funnel-fill text-primary me-2"></i>Filter Data Nilai
            </h6>
            <span class="badge rounded-pill px-3 py-2 mt-2 mt-md-0 border" id="periodStatus" style="font-size: 0.8rem; letter-spacing: 0.5px;"></span>
        </div>

        <div class="row g-4 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label text-white-50 small mb-2 d-flex align-items-center fw-semibold">
                    <i class="bi bi-calendar3 me-2 text-secondary"></i>Tahun Ajaran
                </label>
                <select id="ayFilter" class="form-select custom-select-dark shadow-sm">
                    <option value="" disabled selected>-- Pilih Tahun Ajaran --</option>
                    <?php foreach ($academicYears as $ay): ?>
                        <option value="<?= esc($ay['id']) ?>"><?= esc($ay['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label text-white-50 small mb-2 d-flex align-items-center fw-semibold">
                    <i class="bi bi-flag-fill me-2 text-secondary"></i>Term / Semester
                </label>
                <select id="termFilter" class="form-select custom-select-dark shadow-sm" disabled>
                    <option value="" disabled selected>-- Pilih Tahun Ajaran Dahulu --</option>
                    <?php foreach ($terms as $term): ?>
                        <option value="<?= esc($term['id']) ?>" data-ay="<?= esc($term['academic_year_id']) ?>">
                            <?= esc($term['semester_name'] ?? '') ?> - <?= esc($term['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label text-white-50 small mb-2 d-flex align-items-center fw-semibold">
                    <i class="bi bi-layers-fill me-2 text-secondary"></i>Grade
                </label>
                <select id="gradeFilter" class="form-select custom-select-dark shadow-sm">
                    <option value="">Semua Grade</option>
                    <?php foreach (array_keys($groupedClasses) as $gradeName): ?>
                        <option value="<?= esc($gradeName) ?>"><?= esc($gradeName) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Empty state -->
    <div class="empty-state" id="emptyState">
        <i class="bi bi-calendar2-week"></i>
        <div class="fw-semibold text-white-50">Pilih Tahun Ajaran &amp; Term dahulu</div>
        <div class="small">Daftar kelas akan muncul setelah keduanya dipilih.</div>
    </div>

    <!-- Class list (hidden sampai AY+Term terpilih) -->
    <div class="d-none" id="classGrid">
        <?php
        $gradeIndex = 0;
        foreach ($groupedClasses as $gradeName => $gradeClasses):
            $theme = $gradeThemes[$gradeIndex % count($gradeThemes)];
        ?>
            <div class="grade-group" data-grade="<?= esc($gradeName) ?>">
                <div class="grade-group-title" style="color: <?= $theme['color'] ?>;">
                    <span class="dot" style="background: <?= $theme['color'] ?>;"></span>
                    <h6><?= esc($gradeName) ?></h6>
                    <span class="count-badge"><?= count($gradeClasses) ?> kelas</span>
                </div>

                <div class="class-card-grid">
                    <?php foreach ($gradeClasses as $class): ?>
                        <?php
                        $baseHref = base_url('gradebook') . '?' . http_build_query([
                            'subject_id' => $subjectId ?? '',
                            'class_id'   => $class['id'],
                        ]);
                        ?>
                        <a href="#"
                           class="class-card"
                           data-base-href="<?= esc($baseHref) ?>"
                           style="--accent: <?= $theme['color'] ?>; --glow: <?= $theme['glow'] ?>;"
                           target="_blank"
                           >
                            <div class="card-top">
                                <div class="icon-badge"><i class="bi <?= $theme['icon'] ?>"></i></div>
                                <div class="arrow-btn"><i class="bi bi-arrow-right"></i></div>
                            </div>
                            <div>
                                <div class="class-name"><?= esc($class['class_name']) ?></div>
                                <div class="class-caption">Kelola Nilai</div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
            $gradeIndex++;
        endforeach;
        ?>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function () {

    // Tambahkan parameter isInitialLoad untuk mengecek apakah ini trigger dari load halaman atau klik manual
    function filterTermsByYear(selectedAy, isInitialLoad = false) {
        const $termFilter = $('#termFilter');
        const cachedTerm = $termFilter.val(); // Simpan nilai term yang mungkin di-restore oleh browser

        // Tampilkan/sembunyikan opsi berdasarkan Tahun Ajaran yang dipilih
        $termFilter.find('option[value!=""]').each(function () {
            // Gunakan properti hidden atau display untuk kompatibilitas filter yang stabil
            $(this).toggle($(this).data('ay') == selectedAy);
        });

        if (selectedAy) {
            $termFilter.prop('disabled', false);
            $termFilter.find('option[value=""]').text('-- Pilih Term / Semester --');

            // Cek apakah term yang di-cache oleh browser valid untuk Tahun Ajaran ini
            let isTermValid = false;
            if (cachedTerm) {
                const $selectedOption = $termFilter.find('option[value="' + cachedTerm + '"]');
                if ($selectedOption.length > 0 && $selectedOption.css('display') !== 'none') {
                    isTermValid = true;
                }
            }

            // Jika dipanggil saat awal load dan term-nya valid, biarkan (jangan direset)
            if (isInitialLoad && isTermValid) {
                $termFilter.val(cachedTerm);
            } else {
                // Jika user mengganti manual, reset term ke kosong
                $termFilter.val('');
            }
        } else {
            $termFilter.prop('disabled', true);
            $termFilter.val('');
            $termFilter.find('option[value=""]').text('-- Pilih Tahun Ajaran Dahulu --');
        }
    }

    function refreshState() {
        const ay = $('#ayFilter').val();
        const term = $('#termFilter').val();
        const grade = $('#gradeFilter').val();
        const hasSelection = !!ay && !!term;

        $('.class-card').each(function () {
            const base = $(this).data('base-href');
            if (hasSelection) {
                $(this).attr('href', base + '&academic_year_id=' + encodeURIComponent(ay) + '&term_id=' + encodeURIComponent(term));
            }
        });

        if (hasSelection) {
            $('#classGrid').removeClass('d-none');
            $('#emptyState').addClass('d-none');
        } else {
            $('#classGrid').addClass('d-none');
            $('#emptyState').removeClass('d-none');
        }

        $('.grade-group').each(function () {
            const matches = !grade || $(this).data('grade') === grade;
            $(this).toggleClass('grade-hidden', !matches);
        });

        if (hasSelection) {
            $('#periodStatus')
                .text('Siap Dipilih')
                .removeClass('bg-warning bg-opacity-25 text-warning border-warning')
                .addClass('bg-success bg-opacity-25 text-success border-success');
        } else {
            $('#periodStatus')
                .text('Pilih Tahun & Term')
                .removeClass('bg-success bg-opacity-25 text-success border-success')
                .addClass('bg-warning bg-opacity-25 text-warning border-warning');
        }
    }

    $('#ayFilter').on('change', function () {
        filterTermsByYear($(this).val(), false); // false = user mengubah secara manual
        refreshState();
    });

    $('#termFilter').on('change', refreshState);
    $('#gradeFilter').on('change', refreshState);

    // ==========================================
    // INITIALIZATION SAAT HALAMAN DILAKUKAN LOAD
    // ==========================================
    const initialAy = $('#ayFilter').val();
    if (initialAy) {
        // Jika ada histori Tahun Ajaran di browser (karena user klik tombol Back)
        filterTermsByYear(initialAy, true); // true = ini adalah initial load
    }
    refreshState();

});
</script>
<?= $this->endSection() ?>