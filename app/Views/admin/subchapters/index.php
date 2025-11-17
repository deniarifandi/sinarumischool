<h2>Sub-Chapters of: <?= $chapter['chapter_name'] ?></h2>
<h4>Subject: <?= $subject['subject_name'] ?></h4>

<a href="<?= base_url('admin/subchapters/create/'.$chapter['id']) ?>">+ Add Sub-Chapter</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Order</th>
        <th>Code</th>
        <th>Sub-Chapter</th>
        <th>Description</th>
        <th>Actions</th>
        <th>Objective</th>
    </tr>

    <?php foreach ($subs as $s): ?>
    <tr>
        <td><?= $s['order_number'] ?></td>
        <td><?= $s['sub_code'] ?></td>
        <td><?= $s['sub_name'] ?></td>
        <td><?= $s['description'] ?></td>
        <td>
            <a href="<?= base_url('admin/subchapters/edit/'.$s['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/subchapters/delete/'.$s['id']) ?>" onclick="return confirm('Delete sub-chapter?')">Delete</a>
        </td>
        <td>
            <a href="<?= base_url('admin/objectives/'.$s['id']) ?>">Open</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
