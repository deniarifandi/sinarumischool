<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Journal Recap — <?= esc($class['class_name'] ?? 'Class') ?> · <?= esc(date('d M Y', strtotime($dateFrom))) ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 24px 32px;
            background: #fff;
            font-size: 11px;
            line-height: 1.35;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2.5px double #1a1a1a;
            padding-bottom: 8px;
        }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand-logo {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, #6c63ff 0%, #8b5cf6 100%);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 16px;
        }
        .brand-name { font-size: 15px; font-weight: 800; color: #1a1a1a; }
        .brand-sub { font-size: 10px; color: #666; letter-spacing: 0.4px; }
        .doc-id { text-align: right; font-size: 10px; color: #666; }
        .doc-id strong { display: block; font-size: 12px; color: #1a1a1a; }

        /* Compact recap summary */
        table.summary {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 12px 0;
            font-size: 11px;
        }
        table.summary th {
            background: #6c63ff;
            color: #fff;
            font-weight: 600;
            padding: 4px 8px;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.summary td {
            border: 1px solid #cfcfcf;
            padding: 4px 8px;
            text-align: center;
            font-weight: 700;
        }
        table.summary td.labeln {
            background: #f0eefe;
            border: 1px solid #cfcfcf;
            color: #4a3f9a;
            font-size: 10px;
            text-align: right;
            font-weight: 600;
        }

        /* Journal listing table: one row per journal */
        table.recap {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-top: 4px;
        }
        table.recap th {
            background: #f4f4f7;
            padding: 5px 7px;
            text-align: left;
            font-weight: 700;
            color: #1a1a1a;
            border: 1px solid #cfcfcf;
            font-size: 10px;
        }
        table.recap td {
            border: 1px solid #cfcfcf;
            padding: 4px 7px;
            vertical-align: top;
            text-align: left;
        }
        table.recap .cno { text-align: center; font-weight: 700; width: 4%; }
        table.recap .cdate { white-space: nowrap; }
        table.recap .cjp { text-align: center; width: 6%; }
        table.recap .muted { color: #999; }

        .section-title {
            font-weight: 700;
            font-size: 11px;
            color: #6c63ff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 3px 0;
        }

        .footer {
            margin-top: 14px;
            border-top: 1px solid #ddd;
            padding-top: 6px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }

        .toolbar {
            position: fixed;
            top: 16px;
            right: 16px;
            display: flex;
            gap: 8px;
            z-index: 100;
        }
        .toolbar button, .toolbar a {
            background: #6c63ff;
            color: #fff;
            border: 0;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            font-family: inherit;
        }
        .toolbar a.secondary { background: #888; }

        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
            table.summary th, table.summary td, table.recap th, table.recap td { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨️ Print Recap</button>
        <a href="<?= base_url('journal/class/' . (int)$class['id']) ?>" class="secondary">Back</a>
    </div>

    <div class="header">
        <div class="brand">
            <div class="brand-logo">S</div>
            <div>
                <div class="brand-name">Sinarumi School</div>
                <div class="brand-sub">JOURNAL RECAP — WALI KELAS</div>
            </div>
        </div>
        <div class="doc-id">
            <strong>
                Kelas: <?= esc(($class['grade_name'] ?? '') . ' ' . ($class['class_name'] ?? '-')) ?>
            </strong>
            <?php if ($dateFrom === $dateTo): ?>
                <?= esc(date('l, d F Y', strtotime($dateFrom))) ?>
            <?php else: ?>
                <?= esc(date('d M Y', strtotime($dateFrom))) ?> — <?= esc(date('d M Y', strtotime($dateTo))) ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Compact summary strip: one row -->
    <table class="summary">
        <tr>
            <th>Periode</th>
            <th>Journal</th>
            <th>Total JP</th>
            <th style="display:none">Mata Pelajaran</th>
            <th style="display:none">Guru</th>
            <th>Dicetak</th>
        </tr>
        <tr>
            <td>
                <?php if ($dateFrom === $dateTo): ?>
                    <?= esc(date('d M Y', strtotime($dateFrom))) ?>
                <?php else: ?>
                    <?= esc(date('d M Y', strtotime($dateFrom))) ?> s/d <?= esc(date('d M Y', strtotime($dateTo))) ?>
                <?php endif; ?>
            </td>
            <td><?= count($journals) ?></td>
            <td><?= (int)$totalJp ?> JP</td>
            <td style="display:none"><?= $subjectNames ? esc(implode(', ', $subjectNames)) : '—' ?></td>
            <td style="display:none"><?= $teacherNames ? esc(implode(', ', $teacherNames)) : '—' ?></td>
            <td><?= esc(date('d M Y H:i')) ?></td>
        </tr>
    </table>

    <?php if (empty($journals)): ?>
        <div style="border:1px solid #cfcfcf;padding:14px;color:#999;">
            Tidak ada teaching journal pada periode tersebut.
        </div>
    <?php else: ?>
        <div class="section-title">Detail Journal</div>
        <table class="recap">
            <thead>
                <tr>
                    <th class="cno">#</th>
                    <th>Date</th>
                    <th>Mata Pelajaran</th>
                    <th>Unit / Sub-Unit</th>
                    <th>Guru</th>
                    <th class="cjp">JP</th>
                    <th>Ringkasan Kegiatan</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($journals as $i => $j): ?>
                <?php
                    $unitParts = [];
                    if (!empty($j['unit_name']))    $unitParts[] = $j['unit_name'];
                    if (!empty($j['subunit_name'])) $unitParts[] = '› ' . $j['subunit_name'];
                    $unitLabel = $unitParts ? implode(' ', $unitParts) : null;
                ?>
                <tr>
                    <td class="cno"><?= $i + 1 ?></td>
                    <td class="cdate"><?= esc(date('d M', strtotime($j['date']))) ?></td>
                    <td><?= esc($j['subject_name'] ?? '-') ?></td>
                    <td><?= $unitLabel ? esc($unitLabel) : '<span class="muted">—</span>' ?></td>
                    <td><?= esc($j['teacher_name'] ?? '-') ?></td>
                    <td class="cjp"><?= esc($j['periods'] ?? '-') ?></td>
                    <td>
                        <?= !empty($j['activities']) ? esc($j['activities']) : '' ?>
                        <?php if (!empty($j['notes'])): ?>
                            <span class="muted"><em>· Refleksi: <?= esc($j['notes']) ?></em></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        Dokumen dicetak otomatis dari sistem Sinarumi School ·
        Kelas: <?= esc($class['class_name'] ?? '-') ?> ·
        Periode: <?= esc(date('d M Y', strtotime($dateFrom))) ?><?= $dateFrom !== $dateTo ? ' s/d ' . esc(date('d M Y', strtotime($dateTo))) : '' ?>
    </div>

    <script>
        if (window.location.search.includes('autoprint=1')) {
            window.addEventListener('load', () => setTimeout(() => window.print(), 300));
        }
    </script>
</body>
</html>