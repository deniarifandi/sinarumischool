<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<?php

$bulanList = [
    'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
];

$dplOptions = [
    1   => 'Beriman & Bertakwa',
    2   => 'Mandiri',
    4   => 'Bernalar Kritis',
    8   => 'Kreatif',
    16  => 'Gotong Royong',
    32  => 'Berkebinekaan Global',
    64  => 'Komunikatif',
    128 => 'Berakhlak Mulia'
];

$selectedDpl = isset($lessonplan['dpl'])
    ? (int)$lessonplan['dpl']
    : 0;

$defaultText = [

    'pedagogis' => 'Guru menggunakan pendekatan pembelajaran aktif dan menyenangkan.',

    'kemitraan' => 'Melibatkan komunikasi dengan orang tua terkait kegiatan belajar.',

    'alatbahan' => 'Kertas, alat tulis, media pembelajaran dan bahan pendukung.',

    'sumber' => 'Lingkungan sekitar, buku cerita, dan media visual.',

    'pembukaan' => '- Masuk kelas dan berdoa bersama.
- Membuat kesepakatan bersama.
- Apersepsi.',

    'inti' => 'Peserta didik melakukan kegiatan inti sesuai tujuan pembelajaran.',

    'penutup' => 'Guru melakukan refleksi dan menutup kegiatan belajar.',

    'sambut1' => 'Guru menyambut peserta didik.',
    'sambut2' => 'Peserta didik menyimpan barang.',
    'sambut3' => 'Guru mengajak berdoa.',
    'sambut4' => 'Guru mengecek kehadiran.',
    'sambut5' => 'Guru melakukan ice breaking.',

    'inti1' => 'Guru menjelaskan kegiatan.',
    'inti2' => 'Peserta didik melakukan observasi.',
    'inti3' => 'Peserta didik mencoba kegiatan.',
    'inti4' => 'Guru mendampingi kegiatan.',
    'inti5' => 'Peserta didik menyimpulkan kegiatan.',
];

$fieldLabels = [

    'pedagogis' => 'Strategi Pedagogis',
    'kemitraan' => 'Kemitraan dengan Orang Tua',
    'alatbahan' => 'Alat dan Bahan',
    'sumber'    => 'Sumber Belajar',

    'pembukaan' => 'Kegiatan Pembukaan',
    'inti'      => 'Kegiatan Inti',
    'penutup'   => 'Kegiatan Penutup',

    'inti1' => 'Langkah Inti 1',
    'inti2' => 'Langkah Inti 2',
    'inti3' => 'Langkah Inti 3',
    'inti4' => 'Langkah Inti 4',
    'inti5' => 'Langkah Inti 5',
];

?>

<div class="glass-card">

    <h5 class="mb-4">
        <?= isset($lessonplan) ? 'Edit' : 'Tambah' ?> Lesson Plan
    </h5>

    <form action="<?= isset($lessonplan)
        ? base_url('lessonplan/update/'.$lessonplan['id'])
        : base_url('lessonplan/store') ?>"
        method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="class_id"
               value="<?= esc($mainClass['id']) ?>">

        <input type="hidden"
               name="subject_id"
               value="<?= esc($_GET['subject_id'] ?? '') ?>">

        <!-- TOPIK -->
        <div class="mb-3">
            <label>Topik</label>

            <select name="unit_id"
                    class="form-control"
                    required>

                <option value="">-- pilih topik --</option>

                <?php foreach ($units as $u): ?>
                    <option value="<?= $u['id'] ?>"
                        <?= ($lessonplan['unit_id'] ?? '') == $u['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($u['name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- SUB TOPIK -->
        <div class="mb-3">
            <label>Sub Topik</label>

            <select name="subunit_id"
                    class="form-control"
                    required>

                <option value="">-- pilih sub topik --</option>

                <?php foreach ($subunits as $s): ?>
                    <option value="<?= $s['id'] ?>"
                        <?= ($lessonplan['subunit_id'] ?? '') == $s['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($s['subunit_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- SEMESTER -->
        <div class="mb-3">
            <label>Semester</label>

            <select name="semester"
                    class="form-control"
                    required>

                <option value="">-- pilih semester --</option>

                <option value="1"
                    <?= ($lessonplan['semester'] ?? '') == '1'
                        ? 'selected'
                        : '' ?>>
                    1
                </option>

                <option value="2"
                    <?= ($lessonplan['semester'] ?? '') == '2'
                        ? 'selected'
                        : '' ?>>
                    2
                </option>

            </select>
        </div>

        <!-- BULAN -->
        <div class="mb-3">
            <label>Bulan</label>

            <select name="bulan"
                    class="form-control"
                    required>

                <option value="">-- pilih bulan --</option>

                <?php foreach ($bulanList as $b): ?>
                    <option value="<?= $b ?>"
                        <?= ($lessonplan['bulan'] ?? '') == $b
                            ? 'selected'
                            : '' ?>>

                        <?= $b ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- DPL -->
        <div class="mb-4">
            <label class="mb-2">DPL (Profil Lulusan)</label>

            <?php foreach ($dplOptions as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="dpl[]"
                           value="<?= $val ?>"
                           <?= ($selectedDpl & $val)
                                ? 'checked'
                                : '' ?>>

                    <label class="form-check-label">
                        <?= $label ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>

        <!-- AGAMA -->
        <div class="mb-3">
            <label>Agama 1</label>

            <select name="agama1" class="form-control">
                <option value="">-- pilih --</option>

                <?php foreach ($agama as $a): ?>
                    <option value="<?= $a['id'] ?>"
                        <?= ($lessonplan['agama1'] ?? '') == $a['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($a['objective_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Agama 2</label>

            <select name="agama2" class="form-control">
                <option value="">-- pilih --</option>

                <?php foreach ($agama as $a): ?>
                    <option value="<?= $a['id'] ?>"
                        <?= ($lessonplan['agama2'] ?? '') == $a['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($a['objective_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- JATI -->
        <div class="mb-3">
            <label>Jati Diri 1</label>

            <select name="jati1" class="form-control">
                <option value="">-- pilih --</option>

                <?php foreach ($jati as $j): ?>
                    <option value="<?= $j['id'] ?>"
                        <?= ($lessonplan['jati1'] ?? '') == $j['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($j['objective_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Jati Diri 2</label>

            <select name="jati2" class="form-control">
                <option value="">-- pilih --</option>

                <?php foreach ($jati as $j): ?>
                    <option value="<?= $j['id'] ?>"
                        <?= ($lessonplan['jati2'] ?? '') == $j['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($j['objective_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- LITERASI -->
        <div class="mb-3">
            <label>Literasi 1</label>

            <select name="dasar1" class="form-control">
                <option value="">-- pilih --</option>

                <?php foreach ($literasi as $l): ?>
                    <option value="<?= $l['id'] ?>"
                        <?= ($lessonplan['dasar1'] ?? '') == $l['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($l['objective_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-4">
            <label>Literasi 2</label>

            <select name="dasar2" class="form-control">
                <option value="">-- pilih --</option>

                <?php foreach ($literasi as $l): ?>
                    <option value="<?= $l['id'] ?>"
                        <?= ($lessonplan['dasar2'] ?? '') == $l['id']
                            ? 'selected'
                            : '' ?>>

                        <?= esc($l['objective_name']) ?>

                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- TEXTAREA -->
        <?php

        $fields = [
            'pedagogis',
            'kemitraan',
            'alatbahan',
            'sumber',
            'pembukaan',
            'inti',
            'penutup',
            'inti1',
            'inti2',
            'inti3',
            'inti4',
            'inti5'
        ];

        ?>

        <?php foreach ($fields as $f): ?>

            <div class="mb-3">

                <label>
                    <?= $fieldLabels[$f] ?? $f ?>
                </label>

                <textarea name="<?= $f ?>"
                          class="form-control"
                          rows="3"><?= $lessonplan[$f] ?? ($defaultText[$f] ?? '') ?></textarea>

            </div>

        <?php endforeach ?>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                <?= isset($lessonplan) ? 'Update' : 'Simpan' ?>
            </button>
        </div>

    </form>

</div>

<?= $this->endSection() ?>