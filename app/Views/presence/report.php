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
                    $timestamp = strtotime($d);
                    $dayName = date('D', $timestamp); // Short day name, e.g. Mon, Tue
                    $dayOfWeek = date('w', $timestamp);
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6); // Sunday=0, Saturday=6
                    $style = $isWeekend ? 'style="color:red;"' : '';
                ?>
                <th <?= $style ?>>
                    <?= $d ?><br><?= $dayName ?>
                </th>
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
                   <td <?= $cellStyle ?>>
    <?php
        switch ($status) {
            case 1:
                echo "✔"; // tick
                break;
            case 2:
                echo "I";
                break;
            case 3:
                echo "S";
                break;
            case 4:
                echo "WFA";
                break;
            default:
                echo $status; // fallback if not 1–4
        }
    ?>
</td>
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
