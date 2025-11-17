<h2><?= $title ?></h2>

<p>Welcome, <b><?= $name ?></b> (<?= $role ?>)</p>

<ul>
    <li><a href="/guru/schedule">Jadwal Mengajar</a></li>
    <li><a href="/guru/attendance">Absensi Kelas</a></li>
    <li><a href="/guru/nilai">Input Nilai</a></li>
</ul>

<a href="<?= base_url(); ?>logout">Logout</a>

<?= json_encode(session()->get('divisions'));  ?>
