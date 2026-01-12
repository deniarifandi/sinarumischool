<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex justify-content-between align-items-center">
            <h6 class="text-white text-capitalize ps-3 mb-0">
                Chapter JP Progress
            </h6>
        </div>
    </div>

    <div class="card-body">

        <!-- FLASH SUCCESS -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- FLASH ERROR -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-sm table-hover align-items-center">
                <thead class="thead-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Subject</th>
                        <th>Chapter</th>
                        <th width="120" class="text-center">JP Target</th>
                        <th width="120" class="text-center">JP Spent</th>
                        <th width="140" class="text-center">Remaining</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($chapters)): ?>
                        <?php $no = 1; foreach ($chapters as $row): ?>
                            <?php
                                $jpTarget  = (int) ($row['jp'] ?? 0);
                                $jpSpent   = (int) ($row['total_jp_spent'] ?? 0);
                                $remaining = $jpTarget - $jpSpent;
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                 <td><?= esc($row['subject_name']) ?></td>
                                <td><?= esc($row['chapter_name']) ?></td>

                                <td class="text-center">
                                    <?= $jpTarget > 0 ? $jpTarget : '<span class="text-muted">-</span>' ?>
                                </td>

                                <td class="text-center">
                                    <?= $jpSpent ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($jpTarget == 0): ?>
                                        <span class="badge bg-success">0</span>
                                    <?php elseif ($remaining > 0): ?>
                                        <span class="badge bg-warning"><?= $remaining ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Done</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No chapter data available
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
