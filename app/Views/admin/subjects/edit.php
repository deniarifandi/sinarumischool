<h2>Edit Subject</h2>

<form action="<?= base_url('admin/subjects/update/'.$subject['id']) ?>" method="post">

    Division:<br>
    <select name="division_id" required>
        <?php foreach ($divisions as $d): ?>
            <option value="<?= $d['id'] ?>" 
                <?= $subject['division_id']==$d['id'] ? 'selected' : '' ?>>
                <?= $d['division_name'] ?>
            </option>
        <?php endforeach ?>
    </select><br><br>

    Subject Code:<br>
    <input type="text" name="subject_code" value="<?= $subject['subject_code'] ?>" required><br>

    Subject Name:<br>
    <input type="text" name="subject_name" value="<?= $subject['subject_name'] ?>" required><br>

    Description:<br>
    <textarea name="description"><?= $subject['description'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
