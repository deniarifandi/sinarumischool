<h2>Chapters for Subject: <?= $subject['subject_name'] ?></h2>

<a href="<?= base_url('admin/chapters/create/'.$subject['id']) ?>">+ Add Chapter</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Order</th>
        <th>Code</th>
        <th>Chapter</th>
        <th>Description</th>
        <th>Actions</th>
        <th>Sub-Chapters</th>
    </tr>

    <?php foreach ($chapters as $c): ?>
    <tr>
        <td><?= $c['order_number'] ?></td>
        <td><?= $c['chapter_code'] ?></td>
        <td><?= $c['chapter_name'] ?></td>
        <td><?= $c['description'] ?></td>
        <td>
            <a href="<?= base_url('admin/chapters/edit/'.$c['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/chapters/delete/'.$c['id']) ?>" onclick="return confirm('Delete chapter?')">Delete</a>
        </td>
        <td>
            <a href="<?= base_url('admin/subchapters/'.$c['id']) ?>">Sub-Chapters</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
