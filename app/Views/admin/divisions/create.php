<h2>Add Division</h2>

<form action="<?= base_url('admin/divisions/store') ?>" method="post">

    Division Name:<br>
    <input type="text" name="division_name" required><br>

    Description:<br>
    <textarea name="description"></textarea><br><br>

    <button type="submit">Save</button>
</form>
