<?php
// 1. Logika Pendukung Data
$dplOptions = [
    1   => 'Beriman & Bertakwa',
    2   => 'Mandiri',
    4   => 'Bernalar Kritis',
    8   => 'Kreatif',
    16  => 'Gotong Royong',
    32  => 'Berkebinekaan Global',
    64  => 'Komunikatif',
    128 => 'Berakhlak Mulia'
];

$selectedDpl = isset($lessonplan['dpl']) ? (int)$lessonplan['dpl'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Print Lesson Plan - <?= esc($lessonplan['unit_name'] ?? 'Doc') ?></title>

    <style>
        /* Konfigurasi Dasar Dokumen */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .document-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Header */
        .document-header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .document-header h2 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }

        /* Tabel Meta Informasi (Atas) */
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 4px 2px;
            vertical-align: top;
            font-size: 10pt;
        }

        .label { font-weight: bold; width: 120px; }
        .colon { width: 10px; text-align: center; }

        /* Section Headings */
        h3 {
            font-size: 12pt;
            margin: 20px 0 10px 0;
            padding: 5px 10px;
            background: #eee;
            border-left: 5px solid #000;
            text-transform: uppercase;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            vertical-align: top;
            text-align: left;
        }

        .data-table th {
            background-color: #f9f9f9;
            width: 30%;
        }

        /* Checkbox DPL Styling */
        .dpl-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }

        .check-item {
            display: flex;
            align-items: center;
            font-size: 9pt;
        }

        .box {
            width: 16px;
            height: 16px;
            border: 1px solid #000;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .checked { background-color: #eee; }

        /* Print Settings */
        @media print {
            body { background: none; }
            .document-container { 
                box-shadow: none; 
                margin: 0; 
                width: 100%; 
                max-width: 100%;
                padding: 0;
            }
            .no-print { display: none; }
            h3 { -webkit-print-color-adjust: exact; background: #eee !important; }
            .checked { background-color: #eee !important; -webkit-print-color-adjust: exact; }
            @page { size: A4; margin: 1.5cm; }
        }

        .btn-print {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="document-container">
    <div class="no-print" style="text-align: right;">
        <button class="btn-print" onclick="window.print()">CETAK SEKARANG</button>
    </div>

    <div class="document-header">
        <h2>RENCANA PELAKSANAAN PEMBELAJARAN (LESSON PLAN)</h2>
    </div>

    <!-- Informasi Utama -->
    <table class="meta-table">
        <tr>
            <td class="label">Kelas</td>
            <td class="colon">:</td>
            <td><?= esc($lessonplan['class_name'] ?? '-') ?></td>
            <td class="label">Semester</td>
            <td class="colon">:</td>
            <td><?= esc($lessonplan['semester'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Topik</td>
            <td class="colon">:</td>
            <td><?= esc($lessonplan['unit_name'] ?? '-') ?></td>
            <td class="label">Bulan</td>
            <td class="colon">:</td>
            <td><?= esc($lessonplan['bulan'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Sub Topik</td>
            <td class="colon">:</td>
            <td colspan="4"><?= esc($lessonplan['subunit_name'] ?? '-') ?></td>
        </tr>
    </table>

    <!-- DPL Section (Checkboxes) -->
    <h3>Profil Pelajar (DPL)</h3>
    <div class="dpl-grid">
        <?php foreach ($dplOptions as $val => $label): ?>
            <?php $isChecked = ($selectedDpl & $val); ?>
            <div class="check-item">
                <div class="box <?= $isChecked ? 'checked' : '' ?>">
                    <?= $isChecked ? '✓' : '' ?>
                </div>
                <span style="<?= $isChecked ? 'font-weight:bold;' : '' ?>">
                    <?= $label ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Strategi & Kemitraan -->
    <h3>Strategi & Kemitraan</h3>
    <table class="data-table">
        <tr>
            <th>Strategi Pedagogis</th>
            <td><?= nl2br(esc($lessonplan['pedagogis'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Kemitraan Orang Tua</th>
            <td><?= nl2br(esc($lessonplan['kemitraan'] ?? '-')) ?></td>
        </tr>
    </table>

    <!-- Tujuan Pembelajaran -->
    <h3>Tujuan Pembelajaran</h3>
    <table class="data-table">
        <tr>
            <th>Agama & Budi Pekerti</th>
            <td>
                1. <?= esc($lessonplan['agama1_name'] ?? $lessonplan['agama1'] ?? '-') ?><br>
                2. <?= esc($lessonplan['agama2_name'] ?? $lessonplan['agama2'] ?? '-') ?>
            </td>
        </tr>
        <tr>
            <th>Jati Diri</th>
            <td>
                1. <?= esc($lessonplan['jati1_name'] ?? $lessonplan['jati1'] ?? '-') ?><br>
                2. <?= esc($lessonplan['jati2_name'] ?? $lessonplan['jati2'] ?? '-') ?>
            </td>
        </tr>
        <tr>
            <th>Literasi & STEAM</th>
            <td>
                1. <?= esc($lessonplan['dasar1_name'] ?? $lessonplan['dasar1'] ?? '-') ?><br>
                2. <?= esc($lessonplan['dasar2_name'] ?? $lessonplan['dasar2'] ?? '-') ?>
            </td>
        </tr>
    </table>

    <!-- Kegiatan -->
    <h3>Langkah-Langkah Kegiatan</h3>
    <table class="data-table">
        <tr>
            <th>Pembukaan</th>
            <td><?= nl2br(esc($lessonplan['pembukaan'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Inti (Deskripsi)</th>
            <td><?= nl2br(esc($lessonplan['inti'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Langkah-Langkah Inti</th>
            <td>
                <ol style="margin: 0; padding-left: 15px;">
                    <li><?= esc($lessonplan['inti1'] ?? '-') ?></li>
                    <li><?= esc($lessonplan['inti2'] ?? '-') ?></li>
                    <li><?= esc($lessonplan['inti3'] ?? '-') ?></li>
                    <li><?= esc($lessonplan['inti4'] ?? '-') ?></li>
                    <li><?= esc($lessonplan['inti5'] ?? '-') ?></li>
                </ol>
            </td>
        </tr>
        <tr>
            <th>Penutup</th>
            <td><?= nl2br(esc($lessonplan['penutup'] ?? '-')) ?></td>
        </tr>
    </table>

    <!-- Media -->
    <h3>Media & Sumber</h3>
    <table class="data-table">
        <tr>
            <th>Alat & Bahan</th>
            <td><?= nl2br(esc($lessonplan['alatbahan'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Sumber Belajar</th>
            <td><?= nl2br(esc($lessonplan['sumber'] ?? '-')) ?></td>
        </tr>
    </table>

    <!-- Tanda Tangan (Opsional) -->
    <div style="margin-top: 40px; display: flex; justify-content: space-between; text-align: center;">
        <div style="width: 200px;">
            Mengetahui,<br>Kepala Sekolah<br><br><br><br>( ____________________ )
        </div>
        <div style="width: 200px;">
            Malang, <?= date('d F Y') ?><br>Guru Kelas<br><br><br><br>( ____________________ )
        </div>
    </div>
</div>

</body>
</html>