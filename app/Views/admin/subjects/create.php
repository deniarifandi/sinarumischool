<h2>Add Subject</h2>

<form action="<?= base_url('admin/subjects/store') ?>" method="post">

    Division:<br>
    <select name="division_id" required>
        <?php foreach ($divisions as $d): ?>
            <option value="<?= $d['id'] ?>" <?= $d['id']==$active_division?'selected':'' ?>>
                <?= $d['division_name'] ?>
            </option>
        <?php endforeach ?>
    </select><br><br>

    Subject Code:<br>
    <input type="text" name="subject_code" required><br>

    Subject Name:<br>
    <input type="text" name="subject_name" required><br>

    Description:<br>
    <textarea name="description"></textarea><br><br>

    <button type="submit">Save</button>
</form>
