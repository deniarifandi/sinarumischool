<h2>Division Management</h2>

<a href="<?= base_url('admin/divisions/create') ?>">+ Add Division</a><br><br>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<table border="1" cellpadding="8">
    <tr>
        <th>Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($divisions as $d): ?>
    <tr>
        <td><?= $d['division_name'] ?></td>
        <td><?= $d['description'] ?></td>
        <td>
            <a href="<?= base_url('admin/divisions/edit/'.$d['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/divisions/delete/'.$d['id']) ?>" onclick="return confirm('Delete division?')">Delete</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
