<h2>Teaching Journal</h2>

<a href="<?= base_url('journal/create') ?>">+ Add Entry</a><br><br>

<table border="1" cellpadding="8">
    <tr>
        <th>Date</th>
        <th>Class</th>
        <th>Subject</th>
        
        <th>Activities</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($journals as $j): ?>
    <tr>
        <td><?= $j['date'] ?></td>
        <td><?= $j['class_name'] ?></td>
        <td><?= $j['subject_name'] ?></td>
      
        <td><?= $j['activities'] ?></td>
        <td>
            <a href="<?= base_url('journal/edit/'.$j['id']) ?>">Edit</a> |
            <a href="<?= base_url('journal/delete/'.$j['id']) ?>" onclick="return confirm('Delete entry?')">Delete</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
