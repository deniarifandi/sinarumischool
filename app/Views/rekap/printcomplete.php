<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver/dist/FileSaver.min.js"></script>

<style>
    /* Global Styles & Typography */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }
    
    /* Layout & Spacing */
    .division-sheet {
        page-break-after: always;
        margin-bottom: 40px;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .division-sheet:last-child {
        page-break-after: auto;
    }

    /* Headings */
    .rekap-heading {
        text-align: center;
        margin: 0 0 8px 0;
        color: #2c3e50;
    }
    .rekap-division {
        text-align: right;
        margin: 0 0 15px 0;
        font-weight: bold;
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 5px;
    }

    /* Table Styles */
    .rekap-table {
        font-size: 11px; 
        width: 100%; 
        border-collapse: collapse;
    }
    .rekap-table th, .rekap-table td {
        border: 1px solid #dee2e6;
        padding: 8px 6px;
        vertical-align: middle;
    }
    .rekap-table thead th {
        background-color: #f8f9fa;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    .rekap-table tbody tr:hover {
        background-color: #f1f3f5;
    }

    /* Button Style */
    .btn-export {
        background-color: #198754;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background-color 0.2s;
    }
    .btn-export:hover {
        background-color: #157347;
    }
</style>

<button class="btn-export" onclick="exportToExcel()">
    📊 Export to Excel
</button>

<br><br>

<?php
// Helper Format Tanggal Indonesia
if (!function_exists('formatTanggalIndo')) {
    function formatTanggalIndo($tanggal) {
        $bulanList = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $pecah = explode('-', $tanggal);
        return (int)$pecah[0] . ' ' . $bulanList[(int)$pecah[1]] . ' ' . $pecah[2];
    }
}

$formattedStart = formatTanggalIndo($dateStart);
$formattedEnd = formatTanggalIndo($dateEnd);

// Array untuk kolom per-hari di tabel
$hariIndo = [
    'Sunday'    => 'Min',
    'Monday'    => 'Sen',
    'Tuesday'   => 'Sel',
    'Wednesday' => 'Rab',
    'Thursday'  => 'Kam',
    'Friday'    => 'Jum',
    'Saturday'  => 'Sab'
];

$bulanIndoSingkat = [
    'January'   => 'Jan',
    'February'  => 'Feb',
    'March'     => 'Mar',
    'April'     => 'Apr',
    'May'       => 'Mei',
    'June'      => 'Jun',
    'July'      => 'Jul',
    'August'    => 'Agt',
    'September' => 'Sep',
    'October'   => 'Okt',
    'November'  => 'Nov',
    'December'  => 'Des'
];
?>

<?php foreach ($divisions as $di => $div): ?>
<div class="division-sheet">
    <h2 class="rekap-heading">Laporan Absensi Bulanan</h2>
    <h3 class="rekap-heading"><?= $startMonth ?> - <?= $endMonth ?></h3>
    <h4 class="rekap-division">Divisi: <?= $div['name'] ?></h4>

    <table id="tableRekap<?= $di ?>" class="rekap-table" data-division="<?= esc($div['name']) ?>">
        <thead>
        <tr class="rekap-title-row">
            <th colspan="<?= count($dates) + 7 ?>" style="text-align: center; font-size: 14px; font-weight: bold; padding: 12px; background-color: #e9ecef; border-bottom: 3px solid #ced4da;">
                PERIODE: <?= $formattedStart ?> - <?= $formattedEnd ?>
            </th>
        </tr>
        <tr style="text-align: center;">
            <th style="min-width: 120px;">Nama</th>
            <th>Jabatan</th>
            <th>Divisi</th>

            <?php foreach ($dates as $d): ?>
                <?php
                $ts = strtotime($d);
                $isWeekend = in_array(date('w', $ts), [0, 6]);
                
                $namaHari = $hariIndo[date('l', $ts)];
                $tglBlnThn = date('d', $ts) . " " . $bulanIndoSingkat[date('F', $ts)] . "<br>" . date('Y', $ts);
                
                $style = $isWeekend ? 'style="color:#dc3545; background-color:#f8d7da;"' : '';
                ?>
                <th <?= $style ?>>
                    <?= $namaHari ?>,<br><?= $tglBlnThn ?>
                </th>
            <?php endforeach; ?>

            <th>Masuk</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
            <?php 
                // VARIABEL PENAMPUNG UNTUK TOTAL DI BAWAH TABEL
                $sumPresent = 0;
                $sumIzin = 0;
                $sumSakit = 0;
                $sumTotalNominal = 0;

                foreach ($div['rows'] as $row):
                    $countPresent = 0;
                    $countIzin = 0;
                    $countSakit = 0;
                    $total = 0;
                    $nullified = (isset($row->nullified) ? (int)$row->nullified : 0);
                    $fixedValue = (isset($row->fixed) ? (float)$row->fixed : 0);
            ?>
                <tr>
                    <td><?= $row->name ?></td>
                    <td><?= $row->jabatan_nama ?></td>
                    <td><?= $row->division_name ?></td>
                    <?php foreach ($dates as $d):
                        $status = $row->$d ?? ' ';
                        $dayOfWeek = date('w', strtotime($d));
                        $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                        
                        $cellStyle = $isWeekend ? 'style="color:#dc3545; background-color:#fef4f5; text-align:center;"' : 'style="text-align:center;"';

                        switch ($status) {
                            case 1:
                            case 4:
                                $countPresent++;
                                if ($nullified == 0) {
                                    $total += 15000;
                                }
                                break;
                            case 2:
                                $countIzin++;
                                break;
                            case 3:
                                $countSakit++;
                                break;
                        }
                    ?>
                    <td <?= $cellStyle ?>>
                        <?php
                            switch ($status) {
                                case 1:
                                case 4:
                                    echo "<span style='color: #198754; font-weight: bold;'>✔</span>";
                                    break;
                                case 2:
                                    echo "I";
                                    break;
                                case 3:
                                    echo "S";
                                    break;
                                default:
                                    echo "&nbsp;";
                            }
                        ?>
                    </td>
                    <?php endforeach; ?>
                    
                    <td style="text-align:center; font-weight:bold; background-color:#f8f9fa;"><?= $countPresent ?></td>
                    <td style="text-align:center; background-color:#f8f9fa;"><?= $countIzin ?></td>
                    <td style="text-align:center; background-color:#f8f9fa;"><?= $countSakit ?></td>
                    <td
                        style="text-align:right; font-weight: bold; background-color:#e9ecef;"
                        data-value="<?= $nullified == 1 ? 0 : ($nullified == 2 ? $fixedValue : $total); ?>"
                    >
                        <?php
                            // Menghitung nominal valid untuk karyawan ini
                            $rowNominal = ($nullified == 1) ? 0 : (($nullified == 2) ? $fixedValue : $total);
                            
                            // MENAMBAHKAN KE TOTAL KESELURUHAN (AKUMULASI)
                            $sumPresent += $countPresent;
                            $sumIzin += $countIzin;
                            $sumSakit += $countSakit;
                            $sumTotalNominal += $rowNominal;

                            if ($nullified == 1) echo "-";
                            elseif ($nullified == 2) echo "Rp " . number_format($fixedValue, 0, ',', '.');
                            else echo 'Rp ' . number_format($total, 0, ',', '.');
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        
        <!-- BARIS TOTAL KESELURUHAN DI BAWAH TABEL -->
        <tfoot>
            <tr class="rekap-footer-row" style="background-color: #dee2e6; border-top: 2px solid #adb5bd;">
                <td colspan="<?= 3 + count($dates) ?>" style="text-align: right; padding-right: 15px; font-weight: bold; font-size: 13px;">
                    TOTAL KESELURUHAN
                </td>
                <td style="text-align: center; font-weight: bold; font-size: 12px; color: #198754;"><?= $sumPresent ?></td>
                <td style="text-align: center; font-weight: bold; font-size: 12px;"><?= $sumIzin ?></td>
                <td style="text-align: center; font-weight: bold; font-size: 12px;"><?= $sumSakit ?></td>
                <td style="text-align: right; font-weight: bold; font-size: 13px; color: #0d6efd;" data-value="<?= $sumTotalNominal ?>">
                    Rp <?= number_format($sumTotalNominal, 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php endforeach; ?>

<!-- Export function -->
<!-- Export function -->
<script>
async function exportToExcel() {
    const tables = document.querySelectorAll('.rekap-table');
    const workbook = new ExcelJS.Workbook();

    for (const table of tables) {
        const divName = table.getAttribute('data-division') || 'Division';
        const sheetName = (divName.length > 31 ? divName.substring(0, 31) : divName).replace(/[\\\/\?\*\:\[\]]/g, '');
        const worksheet = workbook.addWorksheet(sheetName);

        const rows = table.querySelectorAll('tr');

        rows.forEach((tr, rowIndex) => {
            const htmlCells = tr.querySelectorAll('th,td');
            const values = [];

            // Membaca nilai dan menangani colspan HTML untuk Excel
            htmlCells.forEach(td => {
                let value;
                if (td.dataset.value !== undefined) {
                    value = Number(td.dataset.value);
                } else {
                    value = td.innerText.replace(/\n/g, "\n").trim();
                }
                values.push(value);
                
                // Jika sel memiliki colspan, tambahkan string kosong ke array agar kolom Excel tidak bergeser
                if (td.colSpan && td.colSpan > 1) {
                    for (let i = 1; i < td.colSpan; i++) {
                        values.push(""); 
                    }
                }
            });

            const excelRow = worksheet.addRow(values);
            let currentExcelCol = 1;

            htmlCells.forEach((td, colIndex) => {
                const cell = excelRow.getCell(currentExcelCol);

                // Styling border
                cell.border = { top:{style:'thin'}, left:{style:'thin'}, bottom:{style:'thin'}, right:{style:'thin'} };
                
                // Terapkan border ke sel yang ter-merge agar tabel Excel tidak putus-putus
                if (td.colSpan > 1) {
                    for (let i = 1; i < td.colSpan; i++) {
                        excelRow.getCell(currentExcelCol + i).border = { top:{style:'thin'}, left:{style:'thin'}, bottom:{style:'thin'}, right:{style:'thin'} };
                    }
                }

                cell.alignment = {
                    vertical:'middle',
                    // Jika ini judul total (ada colspan), ratakan ke kanan
                    horizontal: (td.colSpan > 1 && !tr.classList.contains('rekap-title-row')) ? 'right' : 'center',
                    wrapText:true
                };

                if (rowIndex === 0 || rowIndex === 1 || tr.classList.contains('rekap-footer-row')) {
                    cell.font = { bold: true };
                }

                const color = getComputedStyle(td).color;
                if (color === "rgb(220, 53, 69)" || color === "rgb(255, 0, 0)") {
                    cell.font = { ...(cell.font || {}), color:{argb:'FFFF0000'} };
                    cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFF8D7DA' } };
                }

                if (td.dataset.value !== undefined) {
                    cell.numFmt = '"Rp" #,##0';
                    cell.alignment = { horizontal:'right', vertical:'middle' };
                }
                
                // Tambah index sesuai colspan (1 cell dengan colspan 3 = geser 3 kolom)
                currentExcelCol += td.colSpan || 1;
            });
            
            // Logika merge khusus untuk Baris Footer (Total Keseluruhan)
            if (tr.classList.contains('rekap-footer-row')) {
                const firstCell = tr.querySelector('td');
                worksheet.mergeCells(excelRow.number, 1, excelRow.number, firstCell.colSpan);
                
                // Beri warna abu-abu untuk seluruh baris footer
                for (let i = 1; i <= worksheet.columnCount; i++) {
                    excelRow.getCell(i).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDEE2E6' } };
                }
            }
        });

        // Merge baris pertama (Periode)
        const lastCol = worksheet.columnCount;
        worksheet.mergeCells(1, 1, 1, lastCol);
        worksheet.getCell(1, 1).alignment = { horizontal: 'center', vertical: 'middle' };
        worksheet.getCell(1, 1).fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE9ECEF' } };
        
        worksheet.getRow(1).height = 25;
        worksheet.getRow(2).height = 50;

        worksheet.columns.forEach((column, index) => {
            if (index === 0) column.width = 25;
            else if (index === 1) column.width = 20;
            else if (index === 2) column.width = 15;
            else if (index >= worksheet.columnCount - 4) column.width = 12;
            else column.width = 8;
        });
    }

    const buffer = await workbook.xlsx.writeBuffer();

    // --- PERUBAHAN NAMA FILE DI SINI ---
    // Mengambil variabel PHP dan mengganti spasi menjadi underscore (_) agar rapi
    const periodeStart = "<?= $formattedStart ?>".replace(/\s+/g, '_');
    const periodeEnd = "<?= $formattedEnd ?>".replace(/\s+/g, '_');
    const fileName = `Laporan_Absensi_${periodeStart}_sd_${periodeEnd}.xlsx`;

    saveAs(
        new Blob(
            [buffer],
            { type:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" }
        ),
        fileName // Menggunakan variabel fileName yang sudah dibuat
    );
}
</script>