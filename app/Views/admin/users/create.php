<form action="<?= base_url('admin/users/store') ?>" method="post">

    Username: <input type="text" name="username"><br>
    Name: <input type="text" name="name"><br>

    Role:
    <select name="role">
        <option value="admin">Admin</option>
        <option value="guru">Guru</option>
        <option value="siswa">Siswa</option>
        <option value="operator">Operator</option>
    </select><br>

    Password: <input type="password" name="password"><br>

    Divisions:<br>
    <?php foreach ($divisions as $d): ?>
        <label>
            <input type="checkbox" name="divisions[]" value="<?= $d['id'] ?>">
            <?= $d['division_name'] ?>
        </label><br>
    <?php endforeach ?>

    <button type="submit">Save</button>
</form>
