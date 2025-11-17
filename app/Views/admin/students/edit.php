<h2>Edit Student</h2>

<form action="<?= base_url('admin/students/update/'.$student['id']) ?>" method="post">

    Student Code:<br>
    <input type="text" name="student_code" value="<?= $student['student_code'] ?>"><br>

    Name:<br>
    <input type="text" name="name" value="<?= $student['name'] ?>"><br>

    Gender:<br>
    <select name="gender">
        <option value="M" <?= $student['gender']=='M'?'selected':'' ?>>Male</option>
        <option value="F" <?= $student['gender']=='F'?'selected':'' ?>>Female</option>
    </select><br>

    Birthdate:<br>
    <input type="date" name="birthdate" value="<?= $student['birthdate'] ?>"><br>

    Address:<br>
    <textarea name="address"><?= $student['address'] ?></textarea><br>

    Class:<br>
    <select name="class_id">
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" 
                <?= $student['class_id']==$c['id']?'selected':'' ?>>
                <?= $c['class_name'] ?>
            </option>
        <?php endforeach ?>
    </select><br><br>

    <button type="submit">Update</button>
</form>
