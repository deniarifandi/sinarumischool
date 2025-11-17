<h2>Edit Class</h2>

<form action="<?= base_url('admin/classes/update/'.$class['id']) ?>" method="post">

    Division:<br>
    <select name="division_id" required>
        <?php foreach ($divisions as $d): ?>
            <option value="<?= $d['id'] ?>"
                <?= $d['id'] == $class['division_id'] ? 'selected' : '' ?>>
                <?= $d['division_name'] ?>
            </option>
        <?php endforeach ?>
    </select>
    <br><br>

    Class Name:<br>
    <input type="text" name="class_name" value="<?= $class['class_name'] ?>" required><br>

    Description:<br>
    <textarea name="description"><?= $class['description'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
