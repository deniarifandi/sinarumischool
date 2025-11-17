<h2>Edit Division</h2>

<form action="<?= base_url('admin/divisions/update/'.$division['id']) ?>" method="post">

    Division Name:<br>
    <input type="text" name="division_name" value="<?= $division['division_name'] ?>" required><br>

    Description:<br>
    <textarea name="description"><?= $division['description'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
