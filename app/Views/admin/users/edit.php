<form action="<?= base_url('admin/users/update/'.$user['id']) ?>" method="post">

    Username: <input type="text" name="username" value="<?= $user['username'] ?>"><br>
    Name: <input type="text" name="name" value="<?= $user['name'] ?>"><br>

    Role:
    <select name="role">
        <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        <option value="guru"  <?= $user['role']=='guru'?'selected':'' ?>>Guru</option>
        <option value="siswa" <?= $user['role']=='siswa'?'selected':'' ?>>Siswa</option>
        <option value="operator" <?= $user['role']=='operator'?'selected':'' ?>>Operator</option>
    </select><br>

    New Password (optional):
    <input type="password" name="password"><br>

    Divisions:<br>
    <?php foreach ($divisions as $d): ?>
        <label>
            <input 
                type="checkbox" 
                name="divisions[]" 
                value="<?= $d['id'] ?>"
                <?= in_array($d['id'], $userDivisions) ? 'checked' : '' ?>
            >
            <?= $d['division_name'] ?>
        </label><br>
    <?php endforeach ?>

    <button type="submit">Update</button>
</form>
