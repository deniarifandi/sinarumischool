<h2>Add Lesson Objective</h2>

<form action="<?= base_url('admin/objectives/store') ?>" method="post">

    <input type="hidden" name="subchapter_id" value="<?= $subchapter_id ?>">

    Order Number:<br>
    <input type="number" name="order_number"><br>

    Objective Code:<br>
    <input type="text" name="objective_code"><br>

    Objective Text:<br>
    <textarea name="objective_text" required></textarea><br><br>

    <button type="submit">Save</button>
</form>
