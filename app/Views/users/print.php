<?php
$display = static function ($value) {
    return esc($value === null || trim((string) $value) === '' ? '-' : (string) $value);
};
$date = static function ($value) {
    if (!$value || $value === '0000-00-00') {
        return '-';
    }
    $parsed = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    return $parsed && $parsed->format('Y-m-d') === $value ? $parsed->format('d-m-Y') : $value;
};
$sections = [
    'Identitas Karyawan' => [
        'ID User' => $user['id'], 'Nama Lengkap' => $user['name'],
        'Username' => $user['username'], 'Role' => $user['role'] ?? null,
        'NIP' => $user['nip'] ?? null, 'NIK' => $user['nik'] ?? null,
        'Jenis Kelamin' => $user['gender'] ?? null,
        'Tempat Lahir' => $user['placebirth'] ?? null,
        'Tanggal Lahir' => $date($user['datebirth'] ?? null),
        'Agama' => $user['religion'] ?? null, 'Status Pernikahan' => $user['marital'] ?? null,
    ],
    'Kontak dan Rekening' => [
        'Telepon' => $user['phone'] ?? null, 'Alamat' => $user['address'] ?? null,
        'Nomor Rekening BCA' => $user['bca'] ?? null,
    ],
    'Kepegawaian dan BPJS' => [
        'Nama Divisi' => implode(', ', $divisions), 'Jabatan' => implode(', ', $positions),
        'BPJS Kesehatan' => $user['bpjskesehatan'] ?? null,
        'BPJS Ketenagakerjaan' => $user['bpjsketenagakerjaan'] ?? null,
    ],
    'KKB' => [
        'Nomor KKB' => $user['kkbnomor'] ?? null,
        'Tanggal KKB' => $date($user['kkbstart'] ?? null),
        'Masa KKB (Tahun)' => $user['kkb'] ?? null,
    ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Detail Karyawan - <?= $display($user['name']) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #eef1f5; color: #222; font: 12px Arial, sans-serif; }
        .toolbar { max-width: 210mm; margin: 16px auto; display: flex; gap: 12px; align-items: center; padding: 0 12px; }
        .toolbar a, .toolbar button { padding: 9px 14px; border: 1px solid #bbb; border-radius: 4px; background: white; color: #222; font: inherit; text-decoration: none; cursor: pointer; }
        .toolbar button { background: #245ca6; color: white; border-color: #245ca6; }
        main { max-width: 210mm; margin: 0 auto 24px; padding: 15mm; background: white; }
        header { display: flex; align-items: center; justify-content: space-between; gap: 16px; border-bottom: 2px solid #333; margin-bottom: 16px; padding-bottom: 12px; break-inside: avoid; }
        .header-text { min-width: 0; }
        .user-photo { width: 30mm; height: 40mm; flex-shrink: 0; object-fit: contain; border: 1px solid #bbb; }
        .photo-placeholder { display: flex; align-items: center; justify-content: center; color: #666; text-align: center; font-size: 10px; }
        .user-photo[hidden] { display: none; }
        h1 { font-size: 21px; margin: 0 0 6px; }
        header p { margin: 0 0 6px; overflow-wrap: anywhere; }
        h2 { font-size: 13px; background: #eee; border-bottom: 1px solid #bbb; padding: 7px; margin: 16px 0 0; break-after: avoid; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { padding: 6px 8px; border-bottom: 1px solid #ddd; text-align: left; vertical-align: top; overflow-wrap: anywhere; white-space: pre-wrap; }
        th { width: 34%; font-weight: normal; color: #444; }
        tr { break-inside: avoid; }
        footer { margin-top: 18px; font-size: 10px; color: #666; }
        @page { size: A4 portrait; margin: 15mm; }
        @media print {
            body { background: white; }
            .toolbar { display: none; }
            main { margin: 0; padding: 0; max-width: none; }
            #debug-icon, #debug-bar { display: none !important; }
        }
    </style>
</head>
<body>
    <nav class="toolbar" aria-label="Aksi cetak">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        <a href="<?= base_url('users') ?>">Kembali ke Users</a>
    </nav>
    <main>
        <header>
            <div class="header-text">
            <h1>Data Detail Karyawan</h1>
            <p><?= $display($user['name']) ?> | ID: <?= $display($user['id']) ?></p>
            <p><strong>Nama Divisi:</strong> <?= $display(implode(', ', $divisions)) ?></p>
            <p><strong>Jabatan (Position):</strong> <?= $display(implode(', ', $positions)) ?></p>
            <p><strong>Role:</strong> <?= $display($user['role'] ?? null) ?></p>
            </div>
            <?php if (!empty($user['pasfoto'])): ?>
                <img class="user-photo" src="<?= esc(base_url($user['pasfoto']), 'attr') ?>" alt="Pasfoto <?= esc($user['name'], 'attr') ?>" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                <div class="user-photo photo-placeholder" hidden>Foto tidak tersedia</div>
            <?php else: ?>
                <div class="user-photo photo-placeholder">Belum ada foto</div>
            <?php endif; ?>
        </header>
        <?php foreach ($sections as $heading => $fields): ?>
            <h2><?= esc($heading) ?></h2>
            <table aria-label="<?= esc($heading, 'attr') ?>">
                <tbody>
                    <?php foreach ($fields as $label => $value): ?>
                        <tr><th scope="row"><?= esc($label) ?></th><td><?= $display($value) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
        <footer>Dicetak pada <?= esc(date('d-m-Y H:i')) ?></footer>
    </main>
</body>
</html>
