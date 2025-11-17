<h2>User Management</h2>

<a href="<?= base_url('admin/users/create') ?>">+ Add User</a>

<table border="1" cellpadding="8">
    <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['username'] ?></td>
        <td><?= $u['name'] ?></td>
        <td><?= $u['role'] ?></td>
        <td>
            <a href="<?= base_url('admin/users/edit/'.$u['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/users/delete/'.$u['id']) ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
