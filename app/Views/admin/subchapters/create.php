<h2>Add Sub-Chapter</h2>

<form action="<?= base_url('admin/subchapters/store') ?>" method="post">

    <input type="hidden" name="chapter_id" value="<?= $chapter_id ?>">

    Order Number:<br>
    <input type="number" name="order_number"><br>

    Sub-Chapter Code:<br>
    <input type="text" name="sub_code"><br>

    Sub-Chapter Name:<br>
    <input type="text" name="sub_name" required><br>

    Description:<br>
    <textarea name="description"></textarea><br><br>

    <button type="submit">Save</button>
</form>
