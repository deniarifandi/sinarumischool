<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List - <?= esc($className) ?></title>
    <style>
        /* Print & Page Setup */
        @page { size: A4 portrait; margin: 15mm; }
        * { box-sizing: border-box; }
        
        /* Typography & Base */
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 10pt; color: #222; margin: 0; line-height: 1.4; }
        
        /* UI Elements (Hidden on print) */
        .print-button { margin-bottom: 20px; text-align: right; }
        .print-button button { padding: 8px 16px; font-size: 14px; cursor: pointer; background: #0d6efd; color: #fff; border: none; border-radius: 4px; font-weight: 500; }
        .print-button button:hover { background: #0b5ed7; }
        
        /* Header Section */
        .header { text-align: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #222; }
        .header h2 { margin: 0; font-size: 18pt; text-transform: uppercase; letter-spacing: 1px; }
        .header h4 { margin: 5px 0 0; font-size: 12pt; font-weight: normal; color: #555; }
        
        /* Info Section */
        .info { margin-bottom: 15px; display: flex; justify-content: space-between; font-size: 11pt; }
        
        /* Table Styling */
        .student-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .student-table th, .student-table td { border: 1px solid #444; padding: 6px 10px; vertical-align: middle; }
        .student-table th { background-color: #f8f9fa; font-weight: 600; text-align: center; text-transform: uppercase; font-size: 9pt; }
        .student-table .number { width: 5%; text-align: center; }
        .student-table .name { width: 35%; }
        .student-table .score { width: 20%; }
        .student-table tbody tr { height: 35px; } /* Enforce minimum height for handwriting scores */
        
        /* Print Overrides */
        @media print {
            .print-button { display: none; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .header h4 { color: #222; }
            .student-table th, .student-table td { border-color: #000; }
        }
    </style>
</head>
<body>

    <div class="print-button">
        <button onclick="window.print()">🖨️ Print Document</button>
    </div>

    <div class="header">
        <h2>Student List</h2>
        <h4>Class: <?= esc($className) ?></h4>
    </div>

    <div class="info">
        <div><strong>Class:</strong> <?= esc($className) ?></div>
        <div><strong>Date:</strong> ________________________</div>
    </div>

    <table class="student-table">
        <thead>
            <tr>
                <th class="number">No.</th>
                <th class="name">Student Name</th>
                <th class="score">Score 1</th>
                <th class="score">Score 2</th>
                <th class="score">Score 3</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td class="number"><?= $index + 1 ?></td>
                        <td class="name"><?= esc($student['name']) ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; font-style: italic; color: #666;">
                        No students found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>