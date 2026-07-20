
<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/file-saver/dist/FileSaver.min.js"></script>

<h2 style="text-align: center;">Monthly Attendance Report</h2>
<h3 style="text-align: center;"><?= $startMonth ?> - <?= $endMonth ?></h3>
<h4 style="margin-bottom:3px; text-align:right">Division: <?= $division ?></h4>
<br>

<button onclick="exportToExcel()">
    Export to Excel
</button>

<br><br>

<table id="tableRekap" style="font-size:10px; width:100%; border-collapse: collapse;" border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Divisi</th>
            <?php foreach ($dates as $d): ?>
                <?php
                    $timestamp = strtotime($d);
                    $dayName = date('D', $timestamp);
                    $dayOfWeek = date('w', $timestamp);
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $style = $isWeekend ? 'style="color:red;"' : '';
                ?>
                <th <?= $style ?>>
                    <?= $d ?><br><?= $dayName ?>
                </th>
            <?php endforeach; ?>
            <th>Masuk</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): 
            $countPresent = 0;
            $countIzin = 0;
            $countSakit = 0;
            $total = 0;
        ?>
            <tr>
                <td><?= $row->name ?></td>
                <td><?= $row->jabatan_nama ?></td>
                <td><?= $row->division_name ?></td>
                <?php foreach ($dates as $d): 
                    $status = $row->$d ?? ' ';
                    $dayOfWeek = date('w', strtotime($d));
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $cellStyle = $isWeekend ? 'style="color:red; text-align:center;"' : 'style="text-align:center;"';

                    // Count logic
                    switch ($status) {
                        case 1: // Present
                        case 4: // Half day or another valid status
                            $countPresent++;
                            $total += 15000;
                            break;
                        case 2: // Izin
                            $countIzin++;
                            break;
                        case 3: // Sakit
                            $countSakit++;
                            break;
                    }
                ?>
                <td <?= $cellStyle ?>>
                    <?php
                        switch ($status) {
                            case 1:
                            case 4:
                                echo "✔";
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
                <td style="text-align:center;"><?= $countPresent ?></td>
                <td style="text-align:center;"><?= $countIzin ?></td>
                <td style="text-align:center;"><?= $countSakit ?></td>
                <td
                    style="text-align:right;"
                    data-value="<?= $total ?>"
                >
                    Rp <?= number_format($total, 0, ',', '.') ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- SheetJS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Export function -->
<script>
async function exportToExcel() {
    const table = document.getElementById('tableRekap');

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Attendance');

    const rows = table.querySelectorAll('tr');

    rows.forEach((tr, rowIndex) => {
        const cells = tr.querySelectorAll('th, td');

        const excelRow = [];

        cells.forEach(td => {

    let value;

    if (td.dataset.value !== undefined) {
        // Read numeric value from data-value
        value = Number(td.dataset.value);
    } else {
        value = td.innerText.trim();

        // Convert normal numeric cells
        const numeric = value.replace(/[^\d.-]/g, '');

        if (numeric !== '' && !isNaN(numeric)) {
            value = Number(numeric);
        }
    }

    excelRow.push(value);
});

        const row = worksheet.addRow(excelRow);

        cells.forEach((td, colIndex) => {
            const cell = row.getCell(colIndex + 1);

            // Border
            cell.border = {
                top: { style: 'thin' },
                left: { style: 'thin' },
                bottom: { style: 'thin' },
                right: { style: 'thin' }
            };

            // Alignment
            cell.alignment = {
                vertical: 'middle',
                horizontal: 'center'
            };

            // Header
            if (rowIndex === 0) {
                cell.font = { bold: true };
            }

            // Preserve red font
            const color = window.getComputedStyle(td).color;

            if (color === 'rgb(255, 0, 0)') {
                cell.font = {
                    ...(cell.font || {}),
                    color: { argb: 'FFFF0000' }
                };
            }

            // Total column format
            if (colIndex === cells.length - 1 && typeof cell.value === 'number') {
                cell.numFmt = '"Rp" #,##0';
                cell.alignment = {
                    horizontal: 'right'
                };
            }
        });
    });

    // Auto width
    worksheet.columns.forEach(column => {
        let maxLength = 10;

        column.eachCell({ includeEmpty: true }, cell => {
            const len = cell.value ? cell.value.toString().length : 0;
            if (len > maxLength) maxLength = len;
        });

        column.width = maxLength + 2;
    });

    const buffer = await workbook.xlsx.writeBuffer();

    saveAs(
        new Blob(
            [buffer],
            {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }
        ),
        'Attendance_Report.xlsx'
    );
}
</script>
