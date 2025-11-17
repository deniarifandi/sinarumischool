<h2>Objectives of: <?= $sub['sub_name'] ?></h2>
<h4>Chapter: <?= $chapter['chapter_name'] ?> | Subject: <?= $subject['subject_name'] ?></h4>

<a href="<?= base_url('admin/objectives/create/'.$sub['id']) ?>">+ Add Objective</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Order</th>
        <th>Code</th>
        <th>Objective</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($objectives as $o): ?>
    <tr>
        <td><?= $o['order_number'] ?></td>
        <td><?= $o['objective_code'] ?></td>
        <td><?= $o['objective_text'] ?></td>
        <td>
            <a href="<?= base_url('admin/objectives/edit/'.$o['id']) ?>">Edit</a> |
            <a href="<?= base_url('admin/objectives/delete/'.$o['id']) ?>"
               onclick="return confirm('Delete objective?')">Delete</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
