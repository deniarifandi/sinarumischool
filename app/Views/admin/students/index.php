<h2>Students</h2>

<a href="<?= base_url('admin/students/create') ?>">+ Add Student</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Gender</th>
        <th>Class</th>
        <th>Birthdate</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($students as $s): ?>
    <tr>
        <td><?= $s['student_code'] ?></td>
        <td><?= $s['name'] ?></td>
        <td><?= $s['gender'] ?></td>
        <td><?= $s['class_name'] ?></td>
        <td><?= $s['birthdate'] ?></td>
        <td>
            <a href="<?= base_url('admin/students/edit/'.$s['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/students/delete/'.$s['id']) ?>" 
                onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
