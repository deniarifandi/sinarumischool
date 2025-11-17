<h2>Add Student</h2>

<form action="<?= base_url('admin/students/store') ?>" method="post">

    Student Code:<br>
    <input type="text" name="student_code"><br>

    Name:<br>
    <input type="text" name="name"><br>

    Gender:<br>
    <select name="gender">
        <option value="M">Male</option>
        <option value="F">Female</option>
    </select><br>

    Birthdate:<br>
    <input type="date" name="birthdate"><br>

    Address:<br>
    <textarea name="address"></textarea><br>

    Class:<br>
    <select name="class_id">
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
        <?php endforeach ?>
    </select><br><br>

    <button type="submit">Save</button>
</form>
