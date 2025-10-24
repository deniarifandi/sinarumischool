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
                <td><?= $row->guru_nama ?></td>
                <td><?= $row->jabatan_nama ?></td>
                <td><?= $row->divisi_nama ?></td>
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
                <td style="text-align:right;"><?= number_format($total, 0, ',', '.') ?></td>
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
