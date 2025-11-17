<h2>Edit Lesson Objective</h2>

<form action="<?= base_url('admin/objectives/update/'.$objective['id']) ?>" method="post">

    Order Number:<br>
    <input type="number" name="order_number" value="<?= $objective['order_number'] ?>"><br>

    Objective Code:<br>
    <input type="text" name="objective_code" value="<?= $objective['objective_code'] ?>"><br>

    Objective Text:<br>
    <textarea name="objective_text" required><?= $objective['objective_text'] ?></textarea><br><br>

    <button type="submit">Update</button>
</form>
