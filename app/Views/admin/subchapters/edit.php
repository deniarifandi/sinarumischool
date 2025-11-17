<h2>Edit Sub-Chapter</h2>

<form action="<?= base_url('admin/subchapters/update/'.$sub['id']) ?>" method="post">

    Order Number:<br>
    <input type="number" name="order_number" value="<?= $sub['order_number'] ?>"><br>

    Sub-Chapter Code:<br>
    <input type="text" name="sub_code" value="<?= $sub['sub_code'] ?>"><br>

    Sub-Chapter Name:<br>
    <input type="text" name="sub_name" value="<?= $sub['sub_name'] ?>" required><br>

    Description:<br>
    <textarea name="description"><?= $sub['description'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
