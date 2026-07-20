
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

        <?php
        $hari = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];

        $bulan = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember'
        ];
        ?>

        <?php foreach ($dates as $d): ?>

            <?php
            $ts = strtotime($d);

            $isWeekend = in_array(date('w', $ts), [0, 6]);

            $style = $isWeekend
                ? 'style="color:red;"'
                : '';

            $tanggal =
                $hari[date('l', $ts)] . ', ' .
                date('d', $ts) . ' ' .
                $bulan[date('F', $ts)];
            ?>

            <th <?= $style ?>>
                <?= $tanggal ?>
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
                    data-value="<?= $total; ?>"
                >
                    Rp <?= number_format($total, 0, ',', '.'); ?>
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

        const htmlCells = tr.querySelectorAll('th,td');

        const values = [];

        htmlCells.forEach(td => {

            let value;

            // Total column
            if (td.dataset.value !== undefined) {

                value = Number(td.dataset.value);

            } else {

                value = td.innerText.trim();
            }

            values.push(value);

        });

        const excelRow = worksheet.addRow(values);

        htmlCells.forEach((td, colIndex) => {

            const cell = excelRow.getCell(colIndex + 1);

            // border
            cell.border = {
                top:{style:'thin'},
                left:{style:'thin'},
                bottom:{style:'thin'},
                right:{style:'thin'}
            };

            // alignment
            cell.alignment = {
                vertical:'middle',
                horizontal:'center',
                wrapText:true
            };

            // header
            if(rowIndex===0){
                cell.font={
                    bold:true
                };
            }

            // preserve red font
            const color = getComputedStyle(td).color;

            if(color==="rgb(255, 0, 0)"){

                cell.font={
                    ...(cell.font||{}),
                    color:{argb:'FFFF0000'}
                };

            }

            // Total column
            if(td.dataset.value!==undefined){

                cell.numFmt='"Rp" #,##0';

                cell.alignment={
                    horizontal:'right'
                };

            }

        });

    });

    // Auto width
    worksheet.columns.forEach(column=>{

        let max=10;

        column.eachCell({includeEmpty:true},cell=>{

            const len=cell.value
                ?cell.value.toString().length
                :0;

            if(len>max) max=len;

        });

        column.width=max+3;

    });

    const buffer = await workbook.xlsx.writeBuffer();

    saveAs(
        new Blob(
            [buffer],
            {
                type:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            }
        ),
        "Attendance_Report.xlsx"
    );

}

</script>
