<h2>Subject Management</h2>

<a href="<?= base_url('admin/subjects/create') ?>">+ Add Subject</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Code</th>
        <th>Subject</th>
        <th>Description</th>
        <th>Division</th>
        <th>Actions</th>
        <th>Chapter List</th>
    </tr>

    <?php foreach ($subjects as $s): ?>
    <tr>
        <td><?= $s['subject_code'] ?></td>
        <td><?= $s['subject_name'] ?></td>
        <td><?= $s['description'] ?></td>
        <td><?= $s['division_id'] ?></td>
        <td>
            <a href="<?= base_url('admin/subjects/edit/'.$s['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/subjects/delete/'.$s['id']) ?>" onclick="return confirm('Delete subject?')">Delete</a>
        </td>
        <td><a href="<?= base_url('admin/chapters/'.$s['id']) ?>">Open</a></td>
    </tr>
    <?php endforeach ?>
</table>
