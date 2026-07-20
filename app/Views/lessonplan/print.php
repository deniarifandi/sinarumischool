<?php
// Logika Pendukung Data
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

$intiOptions = [
    1 => 'Mindful (Fokus)', 
    2 => 'Meaningful (Bermakna)', 
    4 => 'Joyful (Menyenangkan)'
];

$selectedDpl = isset($lessonplan['dpl']) ? (int)$lessonplan['dpl'] : 0;
$selectedInti = isset($lessonplan['inti']) ? (int)$lessonplan['inti'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Print Lesson Plan - <?= esc($lessonplan['unit_name'] ?? 'Doc') ?></title>

    <style>
        /* Konfigurasi Dasar Dokumen */
        body {
            font-family: 'Times New Roman', Times, serif; /* Font paling ideal & hemat tinta untuk cetak dokumen resmi */
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
        }

        .document-container {
            max-width: 850px;
            margin: 30px auto;
            background: #fff;
            padding: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Tombol Print */
        .btn-print {
            padding: 12px 25px;
            background: #198754; /* Hijau Bootstrap */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 25px;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .btn-print:hover { background: #157347; }

        /* Header Dokumen */
        .document-header {
            text-align: center;
            border-bottom: 4px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .document-header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Tabel Meta Informasi (Atas) */
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }

        .meta-table td {
            padding: 5px;
            vertical-align: top;
            font-size: 12pt;
        }

        .label-meta { width: 130px; font-weight: bold; }
        .colon { width: 15px; text-align: center; }

        /* Section Headings */
        h3 {
            font-size: 12pt;
            margin: 25px 0 10px 0;
            padding: 6px 12px;
            background-color: #e9ecef;
            border: 1px solid #000;
            text-transform: uppercase;
            page-break-after: avoid; /* Mencegah judul tertinggal sendirian di bawah halaman */
        }

        /* Data Tables Utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        
        .data-table tr {
            page-break-inside: avoid; /* Mencegah baris terpotong pergantian halaman */
            page-break-after: auto;
        }

        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 10px 12px;
            vertical-align: top;
            text-align: left;
        }

        .data-table th {
            background-color: #f8f9fa;
            width: 30%;
            font-weight: bold;
        }

        /* Grid Centang (Checkbox) */
        .grid-container {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .check-item {
            display: flex;
            align-items: flex-start;
            font-size: 11pt;
        }

        .box {
            width: 18px;
            height: 18px;
            border: 1px solid #000;
            margin-right: 12px;
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .checked { background-color: #e9ecef; }

        /* List Styling Harian */
        .daily-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .daily-list li {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #ccc;
        }
        
        .daily-list li:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .daily-day {
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
            text-decoration: underline;
        }

        /* Tanda Tangan */
        .signature-area {
            margin-top: 50px;
            display: table;
            width: 100%;
            page-break-inside: avoid;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        /* Print Settings Khusus Kertas */
        @media print {
            body { background: none; }
            .document-container { 
                box-shadow: none; 
                margin: 0; 
                padding: 0;
                width: 100%; 
                max-width: 100%;
            }
            .no-print { display: none !important; }
            
            /* Mengakali background color saat print agar tetap abu-abu */
            h3, .data-table th, .checked { 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
            
            @page { 
                size: A4 portrait; 
                margin: 2cm; 
            }
        }
    </style>
</head>
<body>

<div class="document-container">
    <!-- Tombol Khusus Layar, hilang saat cetak -->
    <div class="no-print" style="text-align: right; border-bottom: 1px solid #ddd; margin-bottom: 30px; padding-bottom: 20px;">
        <button class="btn-print" onclick="window.print()">🖨️ CETAK DOKUMEN INI</button>
        <p style="margin: 0; font-size: 10pt; color: #666;">Tip: Atur ukuran kertas ke <strong>A4</strong> dan pastikan opsi <strong>"Print Background Graphics"</strong> aktif pada pengaturan printer Anda.</p>
    </div>

    <!-- Header Dokumen Resmi -->
    <div class="document-header">
        <h2>Rencana Pelaksanaan Pembelajaran<br>(Lesson Plan)</h2>
    </div>

    <!-- Tabel Informasi Utama -->
    <table class="meta-table">
        <tr>
    <td class="label-meta">Kelas</td>
    <td class="colon">:</td>
    <td style="width:35%;"><?= esc($lessonplan['class_name'] ?? '-') ?></td>

    <td class="label-meta">Guru</td>
    <td class="colon">:</td>
    <td><?= esc($lessonplan['name'] ?? '-') ?></td>
</tr>

<tr>
    <td class="label-meta">Topik Utama</td>
    <td class="colon">:</td>
    <td><?= esc($lessonplan['unit_name'] ?? '-') ?></td>

    <td class="label-meta">Semester</td>
    <td class="colon">:</td>
    <td><?= esc($lessonplan['semester'] ?? '-') ?></td>
</tr>

<tr>
    <td class="label-meta">Sub Topik</td>
    <td class="colon">:</td>
    <td><?= esc($lessonplan['subunit_name'] ?? '-') ?></td>

    <td class="label-meta">Bulan</td>
    <td class="colon">:</td>
    <td><?= esc($lessonplan['bulan'] ?? '-') ?></td>
</tr>
    </table>

    <!-- Bagian 1: Karakter & Profil (Grid Checkbox) -->
    <h3>A. Target Profil & Karakter</h3>
    <div class="grid-container">
        <div style="font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Profil Pelajar (DPL):</div>
        <div class="checkbox-grid" style="margin-bottom: 20px;">
            <?php foreach ($dplOptions as $val => $label): ?>
                <?php $isChecked = ($selectedDpl & $val); ?>
                <div class="check-item">
                    <div class="box <?= $isChecked ? 'checked' : '' ?>"><?= $isChecked ? '✔' : '' ?></div>
                    <span style="<?= $isChecked ? 'font-weight:bold;' : '' ?>"><?= $label ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Karakter Pembelajaran:</div>
        <div class="checkbox-grid">
            <?php foreach ($intiOptions as $val => $label): ?>
                <?php $isChecked = ($selectedInti & $val); ?>
                <div class="check-item">
                    <div class="box <?= $isChecked ? 'checked' : '' ?>"><?= $isChecked ? '✔' : '' ?></div>
                    <span style="<?= $isChecked ? 'font-weight:bold;' : '' ?>"><?= esc($label) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bagian 2: Tujuan & Strategi -->
    <h3>B. Tujuan & Strategi Pembelajaran</h3>
    <table class="data-table">
        <tr>
            <th>Nilai Agama & Moral</th>
            <td>
                1. <?= esc($lessonplan['agama1_name'] ?? $lessonplan['agama1'] ?? 'Belum dipilih') ?><br>
                2. <?= esc($lessonplan['agama2_name'] ?? $lessonplan['agama2'] ?? 'Belum dipilih') ?>
            </td>
        </tr>
        <tr>
            <th>Pengembangan Jati Diri</th>
            <td>
                1. <?= esc($lessonplan['jati1_name'] ?? $lessonplan['jati1'] ?? 'Belum dipilih') ?><br>
                2. <?= esc($lessonplan['jati2_name'] ?? $lessonplan['jati2'] ?? 'Belum dipilih') ?>
            </td>
        </tr>
        <tr>
            <th>Dasar Literasi & STEAM</th>
            <td>
                1. <?= esc($lessonplan['dasar1_name'] ?? $lessonplan['dasar1'] ?? 'Belum dipilih') ?><br>
                2. <?= esc($lessonplan['dasar2_name'] ?? $lessonplan['dasar2'] ?? 'Belum dipilih') ?>
            </td>
        </tr>
        <tr>
            <th>Strategi Mengajar (Pedagogis)</th>
            <td><?= nl2br(esc($lessonplan['pedagogis'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Kemitraan Orang Tua</th>
            <td><?= nl2br(esc($lessonplan['kemitraan'] ?? '-')) ?></td>
        </tr>
    </table>

    <!-- Bagian 3: Pelaksanaan & Media -->
    <h3>C. Rincian Pelaksanaan</h3>
    <table class="data-table">
        <tr>
            <th>Alat, Bahan & Sumber Belajar</th>
            <td>
                <strong>Alat & Bahan:</strong><br><?= nl2br(esc($lessonplan['alatbahan'] ?? '-')) ?><br><br>
                <strong>Sumber Belajar:</strong><br><?= nl2br(esc($lessonplan['sumber'] ?? '-')) ?>
            </td>
        </tr>
        <tr>
            <th>Kegiatan Pembukaan</th>
            <td><?= nl2br(esc($lessonplan['pembukaan'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Kegiatan Inti (Harian)</th>
            <td>
                <ul class="daily-list">
                    <li><span class="daily-day">Hari Senin:</span><?= nl2br(esc($lessonplan['inti1'] ?? '-')) ?></li>
                    <li><span class="daily-day">Hari Selasa:</span><?= nl2br(esc($lessonplan['inti2'] ?? '-')) ?></li>
                    <li><span class="daily-day">Hari Rabu:</span><?= nl2br(esc($lessonplan['inti3'] ?? '-')) ?></li>
                    <li><span class="daily-day">Hari Kamis:</span><?= nl2br(esc($lessonplan['inti4'] ?? '-')) ?></li>
                    <li><span class="daily-day">Hari Jumat:</span><?= nl2br(esc($lessonplan['inti5'] ?? '-')) ?></li>
                </ul>
            </td>
        </tr>
        <tr>
            <th>Kegiatan Penutup</th>
            <td><?= nl2br(esc($lessonplan['penutup'] ?? '-')) ?></td>
        </tr>
        <tr>
            <th>Cara Penilaian (Assessment)</th>
            <td><?= nl2br(esc($lessonplan['assessment'] ?? '-')) ?></td>
        </tr>
    </table>

    <!-- Bagian 4: Tanda Tangan Resmi -->
    <div class="signature-area">
        <div class="signature-box">
            Mengetahui,<br>
            Kepala Sekolah<br>
            <br><br><br><br><br>
            <strong>( ___________________________ )</strong>
        </div>
        <div class="signature-box">
            Malang, <?= date('d F Y') ?><br>
            Guru Kelas<br>
            <br><br><br><br><br>
            <strong>( ___________________________ )</strong>
        </div>
    </div>
</div>

</body>
</html>