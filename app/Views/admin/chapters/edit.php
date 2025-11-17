<h2>Edit Chapter</h2>

<form action="<?= base_url('admin/chapters/update/'.$chapter['id']) ?>" method="post">

    Order Number:<br>
    <input type="number" name="order_number" value="<?= $chapter['order_number'] ?>"><br>

    Chapter Code:<br>
    <input type="text" name="chapter_code" value="<?= $chapter['chapter_code'] ?>"><br>

    Chapter Name:<br>
    <input type="text" name="chapter_name" value="<?= $chapter['chapter_name'] ?>" required><br>

    Description:<br>
    <textarea name="description"><?= $chapter['description'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
