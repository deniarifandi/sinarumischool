<h2 style="text-align: center;">Monthly Attendance Report</h2>
<h3 style="text-align: center;"><?= $startMonth ?> - <?= $endMonth ?></h3>
<h4 style="margin-bottom:3px; text-align:right">Division: <?= $division ?></h4>
<br>

<button onclick="exportToExcel('tableRekap', 'Attendance_Report')">Export to Excel</button>

<br><br>

<table id="tableRekap" style="font-size:10px; width:100%; border-collapse: collapse;" border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Divisi</th>
            <?php foreach ($dates as $d): ?>
                <?php
                    $dayOfWeek = date('w', strtotime($d));
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $style = $isWeekend ? 'style="color:red;"' : '';
                ?>
                <th <?= $style ?>><?= $d ?></th>
            <?php endforeach; ?>
            <th>Count Day</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): 
            $countDay = 0;
            $total = 0;
        ?>
            <tr>
                <td><?= $row->guru_nama ?></td>
                <td><?= $row->jabatan_nama ?></td>
                <td><?= $row->divisi_nama ?></td>
                <?php foreach ($dates as $d): 
                    $status = $row->$d ?? '0';
                    if ($status == 1) {
                        $countDay++;
                        $total += 15000;
                    }

                    $dayOfWeek = date('w', strtotime($d));
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $cellStyle = $isWeekend ? 'style="color:red; text-align:center;"' : 'style="text-align:center;"';
                ?>
                    <td <?= $cellStyle ?>><?= $status ?></td>
                <?php endforeach; ?>
                <td><?= $countDay ?></td>
                <td><?= $total ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- SheetJS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Export function -->
<script>
function exportToExcel(tableID, filename = '') {
    const table = document.getElementById(tableID);
    const workbook = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
    XLSX.writeFile(workbook, filename ? filename + ".xlsx" : "export.xlsx");
}
</script>
