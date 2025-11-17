<h2><?= $title ?></h2>

<p>Welcome, <b><?= $name ?></b> (<?= $role ?>)</p>

<ul>
    <li><a href="/admin/users">Manage Users</a></li>
    <li><a href="/admin/classes">Manage Classes</a></li>
    <li><a href="/admin/settings">Settings</a></li>
</ul>

<a href="<?= base_url(); ?>logout">Logout</a>
