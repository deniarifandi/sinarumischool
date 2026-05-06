<?= $this->extend('main') ?>
<?= $this->section('content') ?>

<div class="glass-card">
    <h5 class="mb-4"><?= isset($lessonplan) ? 'Edit' : 'Tambah' ?> Lesson Plan</h5>

    <form action="<?= isset($lessonplan)
        ? base_url('lessonplan/update/'.$lessonplan['id'])
        : base_url('lessonplan/store') ?>" method="post">

        <?= csrf_field() ?>

        <input type="hidden" name="class_id" value="<?= esc($mainClass['id']) ?>">
        <input type="hidden" name="subject_id" value="<?= $_GET['subject_id'] ?>">
        <!-- TOPIK -->
        <div class="mb-3">
            <label>Topik</label>
            <select name="unit_id" class="form-control" required>
                <option value="">-- pilih topik --</option>
                <?php foreach ($units as $u): ?>
                    <option value="<?= $u['id'] ?>"
                        <?= ($lessonplan['unit_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                        <?= esc($u['name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- SUB TOPIK -->
        <div class="mb-3">
            <label>Sub Topik</label>
            <select name="subunit_id" class="form-control" required>
                <option value="">-- pilih sub topik --</option>
                <?php foreach ($subunits as $s): ?>
                    <option value="<?= $s['id'] ?>"
                        <?= ($lessonplan['subunit_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                        <?= esc($s['subunit_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- SEMESTER -->
        <div class="mb-3">
            <label>Semester</label>
            <select name="semester" class="form-control" required>
                <option value="">-- pilih semester --</option>
                <option value="1" <?= ($lessonplan['semester'] ?? '') == '1' ? 'selected' : '' ?>>1</option>
                <option value="2" <?= ($lessonplan['semester'] ?? '') == '2' ? 'selected' : '' ?>>2</option>
            </select>
        </div>

        <!-- BULAN -->
        <div class="mb-3">
            <label>Bulan</label>
            <?php
            $bulanList = [
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ];
            ?>
            <select name="bulan" class="form-control" required>
                <option value="">-- pilih bulan --</option>
                <?php foreach ($bulanList as $b): ?>
                    <option value="<?= $b ?>"
                        <?= ($lessonplan['bulan'] ?? '') == $b ? 'selected' : '' ?>>
                        <?= $b ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- DPL -->
        <div class="mb-3">
            <label>DPL (Profil Lulusan)</label>

            <?php
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

            $selected = isset($lessonplan['dpl']) ? (int)$lessonplan['dpl'] : 0;
            ?>

            <?php foreach ($dplOptions as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input"
                           type="checkbox"
                           name="dpl[]"
                           value="<?= $val ?>"
                           <?= ($selected & $val) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $label ?></label>
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
                        <?= ($lessonplan['agama1'] ?? '') == $a['id'] ? 'selected' : '' ?>>
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
                        <?= ($lessonplan['agama2'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                        <?= esc($a['objective_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- JATI DIRI -->
        <div class="mb-3">
            <label>Jati Diri 1</label>
            <select name="jati1" class="form-control">
                <option value="">-- pilih --</option>
                <?php foreach ($jati as $j): ?>
                    <option value="<?= $j['id'] ?>"
                        <?= ($lessonplan['jati1'] ?? '') == $j['id'] ? 'selected' : '' ?>>
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
                        <?= ($lessonplan['jati2'] ?? '') == $j['id'] ? 'selected' : '' ?>>
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
                        <?= ($lessonplan['dasar1'] ?? '') == $l['id'] ? 'selected' : '' ?>>
                        <?= esc($l['objective_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Literasi 2</label>
            <select name="dasar2" class="form-control">
                <option value="">-- pilih --</option>
                <?php foreach ($literasi as $l): ?>
                    <option value="<?= $l['id'] ?>"
                        <?= ($lessonplan['dasar2'] ?? '') == $l['id'] ? 'selected' : '' ?>>
                        <?= esc($l['objective_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <!-- TEXTAREAS -->
        <?php
        $fields = [
            'pedagogis','kemitraan','alatbahan','sumber',
            'inti','penutup','pembukaan',
            'sambut1','sambut2','sambut3','sambut4','sambut5',
            'inti1','inti2','inti3','inti4','inti5'
        ];
        ?>

        <?php foreach ($fields as $f): ?>
            <div class="mb-3">
                <label><?= strtoupper($f) ?></label>
                <textarea name="<?= $f ?>" class="form-control" rows="2"><?= $lessonplan[$f] ?? '' ?></textarea>
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