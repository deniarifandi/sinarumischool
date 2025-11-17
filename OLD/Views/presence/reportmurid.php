<h2 style="text-align: center;">Student Monthly Attendance Report</h2>
<h4 style="margin-bottom:3px; text-align:right">Class: <?= $kelompok ?></h4>
<h4 style="margin-bottom:3px; text-align:right">Class Teacher: <?php echo session()->get('nama') ?></h4>
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
                    $dayName = date('D', $timestamp); // Mon, Tue, etc.
                    $dayOfWeek = date('w', $timestamp);
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $style = $isWeekend ? 'style="color:red;"' : '';
                ?>
                <th <?= $style ?>>
                    <?= $d ?><br><?= $dayName ?>
                </th>
            <?php endforeach; ?>
            <th>Hadir</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alpha</th>
            
        </tr>
    </thead>

    <tbody>
        <?php foreach ($results as $row): 
            $hadirCount = 0;
            $sakitCount = 0;
            $izinCount = 0;
            $alphaCount = 0;
        ?>
            <tr>
                <td><?= $row->murid_nama ?></td>
                <td><?= $row->kelompok_nama ?></td>
                <?php foreach ($dates as $d): 
                    $status = $row->$d ?? '0';

                    // Count specific statuses
                    if ($status == 1) $hadirCount++;
                    if ($status == 2) $alphaCount++;
                    if ($status == 3) $sakitCount++;
                    if ($status == 4) $izinCount++;

                    $dayOfWeek = date('w', strtotime($d));
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);
                    $cellStyle = $isWeekend ? 'style="color:red; text-align:center;"' : 'style="text-align:center;"';
                ?>
                    <td <?= $cellStyle ?>>
                        <?php
                        if ($status == 1) {
                            echo '<span style="color:green;">✔</span>'; // Hadir
                        } elseif ($status == 2) {
                            echo '<span style="color:red;">A</span>'; // Alpha
                        } elseif ($status == 3) {
                            echo '<span style="color:orange;">S</span>'; // Sakit
                        } elseif ($status == 4) {
                            echo '<span style="color:blue;">I</span>'; // Izin
                        } else {
                            echo '<span style="color:gray;">-</span>'; // Kosong
                        }
                        ?>
                    </td>
                <?php endforeach; ?>
                
                <td style="text-align:center; color:green;"><?= $hadirCount ?></td>
                <td style="text-align:center; color:orange;"><?= $sakitCount ?></td>
                <td style="text-align:center; color:blue;"><?= $izinCount ?></td>
                <td style="text-align:center; color:red;"><?= $alphaCount ?></td>
                
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
