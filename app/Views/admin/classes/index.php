<h2>Class Management</h2>

<a href="<?= base_url('admin/classes/create') ?>">+ Add Class</a>
<br><br>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red"><?= session()->getFlashdata('error') ?></div>
<?php endif ?>

<table border="1" cellpadding="8">
    <tr>
        <th>Class Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($classes as $c): ?>
    <tr>
        <td><?= $c['class_name'] ?></td>
        <td><?= $c['description'] ?></td>
        <td>
            <a href="<?= base_url('admin/classes/edit/'.$c['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/classes/delete/'.$c['id']) ?>" onclick="return confirm('Delete this class?')">Delete</a>
        </td>
    </tr>
    <?php endforeach ?>

</table>
