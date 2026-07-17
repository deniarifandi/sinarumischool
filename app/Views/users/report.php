<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff KKB Report</title>

    <style>
        *{
            box-sizing:border-box;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            color:#222;
            background:#fff;
            margin:30px;
            font-size:13px;
        }

        h1,h2,h3,h4,h5{
            margin:0;
        }

        .header{
            border-bottom:2px solid #000;
            padding-bottom:15px;
            margin-bottom:25px;
        }

        .header table{
            width:100%;
        }

        .company{
            font-size:22px;
            font-weight:bold;
        }

        .title{
            font-size:18px;
            margin-top:5px;
            font-weight:bold;
        }

        .meta{
            text-align:right;
            font-size:12px;
            line-height:20px;
        }

        .section{
            margin-top:25px;
        }

        .section-title{
            background:#efefef;
            padding:8px;
            font-size:15px;
            font-weight:bold;
            border:1px solid #ccc;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        .summary td{
            border:1px solid #ccc;
            padding:10px;
        }

        .summary td:first-child{
            width:300px;
            font-weight:bold;
        }

        .division-table th,
        .division-table td,
        .watch-table th,
        .watch-table td,
        .duration-table th,
        .duration-table td{
            border:1px solid #bbb;
            padding:8px;
        }

        .division-table th,
        .watch-table th,
        .duration-table th{
            background:#f5f5f5;
            text-align:left;
        }

        .expired{
            color:#c00000;
            font-weight:bold;
        }

        .urgent{
            color:#d97706;
            font-weight:bold;
        }

        .warning{
            color:#ca8a04;
            font-weight:bold;
        }

        .footer{
            margin-top:40px;
            text-align:right;
            font-size:12px;
        }

        @media print{

            .no-print{
                display:none;
            }

            body{
                margin:15px;
            }

            @page{
                margin:15mm;
            }

        }

    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:20px;">
    <button onclick="window.print()">🖨 Print Report</button>
</div>

<div class="header">

    <table>
        <tr>

            <td>

                <div class="company">
                    YOUR COMPANY NAME
                </div>

                <div class="title">
                    STAFF KKB REPORT
                </div>

            </td>

            <td class="meta">

                Generated :
                <?= date('d M Y H:i') ?>

                <br>

                Division :

                <?=
                    $selectedDivisionName ??
                    'All Divisions'
                ?>

            </td>

        </tr>
    </table>

</div>

<div class="section">

    <div class="section-title">
        Summary
    </div>

    <table class="summary">

        <tr>
            <td>Total Staff</td>
            <td><?= esc($totalStaff) ?></td>
        </tr>

        <tr>
            <td>Total Divisions</td>
            <td><?= esc($totalDivisions) ?></td>
        </tr>

        <tr>
            <td>Expired KKB</td>
            <td>
                <?= count(array_filter($kkbNeedsRenewal, fn($k)=>$k['status']=='expired')) ?>
            </td>
        </tr>

        <tr>
            <td>Need Attention</td>
            <td><?= count($kkbNeedsRenewal) ?></td>
        </tr>

    </table>

</div>

<div class="section">

    <div class="section-title">
        Staff by KKB Duration
    </div>

    <table class="duration-table">

        <thead>

            <tr>
                <th>Duration</th>
                <th>Total Staff</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($kkbDurationCounts as $duration=>$count): ?>

            <tr>

                <td><?= esc($duration) ?></td>

                <td><?= esc($count) ?></td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

<div class="section">

    <div class="section-title">
        Staff by Division
    </div>

    <table class="division-table">

        <thead>

        <tr>

            <th>Division</th>

            <th>Total Staff</th>

        </tr>

        </thead>

        <tbody>

        <?php foreach($divisionCounts as $division): ?>

            <tr>

                <td><?= esc($division['name']) ?></td>

                <td><?= esc($division['count']) ?></td>

            </tr>

        <?php endforeach; ?>

        <?php if($unassignedDivisionCount>0): ?>

            <tr>

                <td><em>Unassigned</em></td>

                <td><?= esc($unassignedDivisionCount) ?></td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</div>

<div class="section">

    <div class="section-title">
        KKB Renewal Watchlist
    </div>

    <table class="watch-table">

        <thead>

        <tr>

            <th>No</th>

            <th>Staff</th>

            <th>Username</th>

            <th>Division</th>

            <th>KKB No.</th>

            <th>Duration</th>

            <th>Start</th>

            <th>Expiry</th>

            <th>Days Left</th>

            <th>Status</th>

        </tr>

        </thead>

        <tbody>

        <?php $no=1; ?>

        <?php foreach($kkbNeedsRenewal as $k): ?>

            <tr>

                <td><?= $no++ ?></td>

                <td><?= esc($k['name']) ?></td>

                <td><?= esc($k['username']) ?></td>

                <td>

                    <?php

                    if(!empty($k['divisions'])){

                        echo esc(implode(', ', $k['divisions']));

                    }else{

                        echo 'Unassigned';

                    }

                    ?>

                </td>

                <td><?= esc($k['kkbnomor'] ?? '-') ?></td>

                <td><?= esc($k['kkb_years']) ?> Year(s)</td>

                <td><?= esc($k['kkbstart']) ?></td>

                <td><?= esc($k['expiry']) ?></td>

                <td>

                    <?php

                    if($k['days_left']<0){

                        echo abs($k['days_left']).' days ago';

                    }else{

                        echo $k['days_left'].' days';

                    }

                    ?>

                </td>

                <td class="<?= esc($k['status']) ?>">

                    <?= strtoupper(esc($k['status'])) ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

<div class="footer">

    Printed on <?= date('d F Y H:i:s') ?>

</div>

<script>

window.onload=function(){

    window.print();

};

</script>

</body>
</html>