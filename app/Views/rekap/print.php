<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Rekap Report</title>

<style>

body{
    font-family: Arial, Helvetica, sans-serif;
    font-size:14px;
    margin:40px;
}

h2{
    margin-bottom:5px;
}

.report-info{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    border:1px solid #999;
    padding:6px 8px;
}

th{
    background:#eaeaea;
    text-align:left;
}

.group-row td{
    background:#f5f5f5;
    font-weight:bold;
}

.text-center{
    text-align:center;
}

.print-btn{
    margin-bottom:20px;
}

@media print{
    .print-btn{
        display:none;
    }
}

</style>

</head>
<body>

<div class="print-btn">
    <button onclick="window.print()">Print</button>
</div>

<h2>Rekap Report</h2>

<div class="report-info">
    <strong>Division :</strong> <?= esc($rekaps[0]['users'][0]['division_name'] ?? '-') ?><br>
    <strong>Period :</strong>
    <?= esc($date_start ?? '-') ?> - <?= esc($date_end ?? '-') ?>
</div>


<table>

<thead>
<tr>
    <th style="width:50px">No</th>
    <th>User Role</th>
    <th>User</th>
    <th>Total Presence</th>
    <th>Incentive</th>
    
</tr>
</thead>

<tbody>

<?php foreach ($rekaps as $group): ?>

<tr class="group-row">
    <td colspan="3"><?= esc($group['group']) ?></td>
</tr>

<?php $no = 1; ?>

<?php if (!empty($group['users'])): ?>

    <?php foreach ($group['users'] as $u): ?>

    <tr>
        <td class="text-center"><?= $no++ ?></td>
        <td><?= esc($u['user_role']) ?></td>
       <td><?= $u['user_name'] === 'superadmin' ? '' : esc($u['user_name']) ?></td>
        <td><?= esc($u['total_presence']) ?></td>
        
            <?php if ($u['nullified'] == 0): ?>
                <td>
                <?= esc($u['total_presence'] * 15000) ?>    
                </td>
            <?php else :?>
                <td style="background-color: black;"></td>
            <?php endif ?>
                
        
    </tr>

    <?php endforeach; ?>

<?php else: ?>

<tr>
    <td colspan="3" class="text-center">No data</td>
</tr>

<?php endif; ?>

<?php endforeach; ?>

</tbody>

</table>

<br><br>

<div style="margin-top:60px;">
    <table style="width:100%;border:none;">
        <tr style="border:none;">
            <td style="border:none;width:60%"></td>
            <td style="border:none;text-align:center">
                <p>Authorized by</p>
                <br><br><br>
                <p>________________________</p>
            </td>
        </tr>
    </table>
</div>

</body>
</html>