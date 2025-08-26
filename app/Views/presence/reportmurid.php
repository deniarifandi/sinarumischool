<h2 style="text-align: center;">Laporan Absensi Murid Bulanan</h2>
<h4 style="margin-bottom:3px; text-align:right">Kelompok: <?= $kelompok ?></h4>
<br>

<!-- <button onclick="exportToExcel('tableRekap', 'Attendance_Report')">Export to Excel</button> -->

<br><br>

<table id="tableRekap" style="font-size:10px; width:100%; border-collapse: collapse;" border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Kelompok</th>
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
           
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): 
            $countDay = 0;
            $total = 0;
        ?>
            <tr>
                <td><?= $row->murid_nama ?></td>
                <td><?= $row->kelompok_nama ?></td>
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
                    <td <?= $cellStyle ?>><?php
switch ($status) {
    case 1:
        echo "✔"; // centang
        break;
    case 2:
        echo "A"; // symbol A
        break;
    case 3:
        echo "S"; // sick
        break;
    default:
        echo "-"; // default
}
?></td>
                <?php endforeach; ?>
                <td><?= $countDay ?></td>
               
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
