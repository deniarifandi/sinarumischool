<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teaching Journal — <?= esc($journal['subject_name'] ?? 'Journal') ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1a1a1a;
            margin: 0;
            padding: 32px 40px;
            background: #fff;
            font-size: 13px;
            line-height: 1.5;
        }
        .page {
            max-width: 820px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #1a1a1a;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-logo {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6c63ff 0%, #8b5cf6 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 22px;
        }
        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
        }
        .brand-sub {
            font-size: 12px;
            color: #666;
            letter-spacing: 0.5px;
        }
        .doc-id {
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .doc-id strong {
            display: block;
            font-size: 13px;
            color: #1a1a1a;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 4px 0;
            color: #1a1a1a;
            letter-spacing: 0.5px;
        }
        h1 small {
            display: block;
            font-size: 12px;
            font-weight: 400;
            color: #666;
            margin-top: 2px;
        }
        .meta-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .meta-box {
            border: 1px solid #ddd;
            border-left: 4px solid #6c63ff;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .meta-box .label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 2px;
        }
        .meta-box .value {
            font-weight: 700;
            font-size: 14px;
            color: #1a1a1a;
        }
        table.detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        table.detail th,
        table.detail td {
            border: 1px solid #cfcfcf;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        table.detail th {
            background: #f4f4f7;
            width: 24%;
            font-weight: 700;
            color: #1a1a1a;
        }
        table.detail td {
            background: #fff;
        }
        .section-title {
            font-weight: 700;
            font-size: 12px;
            color: #6c63ff;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin: 18px 0 8px 0;
            border-bottom: 1px solid #ececff;
            padding-bottom: 4px;
        }
        .body-text {
            white-space: pre-line;
            border: 1px solid #cfcfcf;
            border-radius: 6px;
            padding: 12px 14px;
            min-height: 60px;
            background: #fafafa;
        }
        .body-text.empty {
            color: #999;
            font-style: italic;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 56px;
        }
        .sig-block {
            text-align: center;
        }
        .sig-line {
            border-top: 1px solid #1a1a1a;
            padding-top: 6px;
            font-size: 12px;
        }
        .sig-name {
            font-weight: 700;
            font-size: 13px;
        }
        .sig-role {
            font-size: 11px;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
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
            .page { max-width: 100%; }
            .meta-box, table.detail th, table.detail td { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨️ Print</button>
        <a href="<?= base_url('journal') ?>" class="secondary">Back</a>
    </div>

    <div class="page">
        <div class="header">
            <div class="brand">
                <div class="brand-logo">S</div>
                <div>
                    <div class="brand-name">Sinarumi School</div>
                    <div class="brand-sub">TEACHING JOURNAL — GURU MATA PELAJARAN</div>
                </div>
            </div>
            <div class="doc-id">
                <strong>No. Jurnal: TJ-<?= str_pad((string)($journal['id'] ?? 0), 5, '0', STR_PAD_LEFT) ?></strong>
                Dicetak: <?= esc(date('d M Y H:i')) ?>
            </div>
        </div>

        <h1>
            Teaching Journal
            <small><?= esc(date('l, d F Y', strtotime($journal['date']))) ?></small>
        </h1>

        <div class="meta-bar">
            <div class="meta-box">
                <div class="label">Tanggal Mengajar</div>
                <div class="value"><?= esc(date('d M Y', strtotime($journal['date']))) ?></div>
            </div>
            <div class="meta-box">
                <div class="label">Jam Pelajaran</div>
                <div class="value"><?= esc($journal['periods'] ?? '-') ?> JP</div>
            </div>
            <div class="meta-box">
                <div class="label">Kelas</div>
                <div class="value"><?= esc(($journal['grade_name'] ?? '') . ' — ' . ($journal['class_name'] ?? '-')) ?></div>
            </div>
        </div>

        <table class="detail">
            <tr>
                <th>Guru Mata Pelajaran</th>
                <td><?= esc($journal['teacher_name'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Mata Pelajaran</th>
                <td><?= esc($journal['subject_name'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Unit</th>
                <td><?= !empty($journal['unit_name']) ? esc($journal['unit_name']) : '<span style="color:#999">—</span>' ?></td>
            </tr>
            <tr>
                <th>Sub-Unit</th>
                <td><?= !empty($journal['subunit_name']) ? esc($journal['subunit_name']) : '<span style="color:#999">—</span>' ?></td>
            </tr>
        </table>

        <div class="section-title">Ringkasan Kegiatan Pembelajaran</div>
        <div class="body-text <?= empty($journal['activities']) ? 'empty' : '' ?>">
            <?= empty($journal['activities']) ? '(Tidak ada catatan kegiatan.)' : esc($journal['activities']) ?>
        </div>

        <div class="section-title">Catatan / Refleksi Guru</div>
        <div class="body-text <?= empty($journal['notes']) ? 'empty' : '' ?>">
            <?= empty($journal['notes']) ? '(Tidak ada catatan refleksi.)' : esc($journal['notes']) ?>
        </div>

        <div class="signatures">
            <div class="sig-block">
                <div style="height: 60px"></div>
                <div class="sig-line">
                    <div class="sig-name"><?= esc($journal['teacher_name'] ?? '.........................') ?></div>
                    <div class="sig-role">Guru Mata Pelajaran</div>
                </div>
            </div>
            <div class="sig-block">
                <div style="height: 60px"></div>
                <div class="sig-line">
                    <div class="sig-name">_________________________</div>
                    <div class="sig-role">Kepala Sekolah / Wakil</div>
                </div>
            </div>
        </div>

        <div class="footer">
            Dokumen ini dicetak otomatis dari sistem Sinarumi School ·
            ID: <?= (int)($journal['id'] ?? 0) ?> ·
            <?= esc($journal['created_at'] ?? '-') ?>
        </div>
    </div>

    <script>
        // Auto-trigger print dialog when the page loads with ?autoprint=1
        if (window.location.search.includes('autoprint=1')) {
            window.addEventListener('load', () => setTimeout(() => window.print(), 300));
        }
    </script>
</body>
</html>
