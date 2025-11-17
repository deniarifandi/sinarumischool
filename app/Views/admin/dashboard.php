<h2>Admin Dashboard</h2>

<p>Welcome, <?= session()->get('name') ?></p>

<a href="<?= base_url('admin/users') ?>">User Management</a><br>

<a href="<?= base_url('admin/classes') ?>">Class Management</a><br>

<a href="<?= base_url('admin/students') ?>">Student Management</a><br>

<a href="<?= base_url('admin/subjects') ?>">Subjects Management</a><br>

<a href="<?= base_url('admin/divisions') ?>">Divisions Management</a><br>

<a href="<?= base_url('journal') ?>">Teaching Journal</a><br>

<a href="<?= base_url('gradebook') ?>">Gradebook</a><br>

<a href="<?= base_url('logout') ?>">Logout</a>