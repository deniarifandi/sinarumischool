<h2>Add Class</h2>

<form action="<?= base_url('admin/classes/store') ?>" method="post">

    Division:<br>
    <select name="division_id" required>
        <?php foreach ($divisions as $d): ?>
            <option value="<?= $d['id'] ?>"
                <?= $d['id'] == $active_division ? 'selected' : '' ?>>
                <?= $d['division_name'] ?>
            </option>
        <?php endforeach ?>
    </select>
    <br><br>

    Class Name:<br>
    <input type="text" name="class_name" required><br>

    Description (optional):<br>
    <textarea name="description"></textarea><br><br>

    <button type="submit">Save</button>
</form>
