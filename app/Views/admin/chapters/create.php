<h2>Add Chapter</h2>

<form action="<?= base_url('admin/chapters/store') ?>" method="post">

    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

    Order Number:<br>
    <input type="number" name="order_number"><br>

    Chapter Code:<br>
    <input type="text" name="chapter_code"><br>

    Chapter Name:<br>
    <input type="text" name="chapter_name" required><br>

    Description:<br>
    <textarea name="description"></textarea><br><br>

    <button type="submit">Save</button>
</form>
