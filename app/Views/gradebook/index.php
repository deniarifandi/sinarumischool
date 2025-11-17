<h2>Gradebook</h2>

<style>
    table {
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
    }
</style>

<!-- FILTER PANEL -->
<form method="get" action="<?= base_url('gradebook') ?>">
    <label>Class:</label>
    <select name="class_id" onchange="this.form.submit()">
        <option value="">-- All Classes --</option>
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" 
                <?= isset($_GET['class_id']) && $_GET['class_id'] == $c['id'] ? 'selected' : '' ?>>
                <?= $c['class_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    &nbsp;

    <label>Subject:</label>
    <select name="subject_id" onchange="this.form.submit()">
        <option value="">-- All Subjects --</option>
        <?php foreach ($subjects as $s): ?>
            <option value="<?= $s['id'] ?>"
                <?= isset($_GET['subject_id']) && $_GET['subject_id'] == $s['id'] ? 'selected' : '' ?>>
                <?= $s['subject_name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<br>

<!-- MAIN TABLE -->
<table border="1" width="100%">
    <tr style="background:#eee">
        <th>Student</th>
        <th>Class</th>
        <th>Subject</th>
        <th>Chapter</th>
        <th>Subchapter</th>
        <th>Objective</th>
        <th>Score</th>
        <th>Teacher</th>
        <th>Date</th>
    </tr>

    <?php foreach ($grades as $g): ?>
    <tr>
        <td><?= $g['student_name'] ?></td>
        <td><?= $g['class_name'] ?></td>
        <td><?= $g['subject_name'] ?></td>

        <!-- Load chapter name safely -->
        <td>
            <?php 
                $chap = $db->table('chapters')->where('id', $g['chapter_id'])->get()->getRowArray();
                echo $chap ? $chap['chapter_name'] : '-';
            ?>
        </td>

        <!-- Load subchapter name safely -->
        <td>
            <?php 
                $sub = $db->table('sub_chapters')->where('id', $g['subchapter_id'])->get()->getRowArray();
                echo $sub ? $sub['sub_name'] : '-';
            ?>
        </td>

        <!-- Load objective text -->
        <td>
            <?php 
                $obj = $db->table('lesson_objectives')->where('id', $g['objective_id'])->get()->getRowArray();
                echo $obj ? $obj['objective_text'] : '-';
            ?>
        </td>

        <td><strong><?= $g['score'] ?></strong></td>

        <!-- Teacher name -->
        <td>
            <?php
                $teacher = $db->table('users')->where('id', $g['teacher_id'])->get()->getRowArray();
                echo $teacher ? $teacher['name'] : '-';
            ?>
        </td>

        <td><?= date('d M Y', strtotime($g['created_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
