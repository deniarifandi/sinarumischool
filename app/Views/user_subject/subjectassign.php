<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<div class="glass-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-0">Assign Subjects</h5>

            <small class="text-white-50">
                Division:
                <?= esc(
                    $division['name']
                    ?? $division['division_name']
                    ?? $division['id']
                ) ?>
            </small>
        </div>
    </div>

    <!-- USER SELECT -->
    <form method="get"
          action="<?= base_url('user-subject') ?>"
          class="mb-4">

        <input type="hidden"
               name="division"
               value="<?= esc($divisionId) ?>">

        <div class="row align-items-end">

            <div class="col-md-6">

                <label class="form-label">
                    User
                </label>

                <select name="user_id"
                        class="form-select"
                        onchange="this.form.submit()">

                    <option value="">
                        -- Select User --
                    </option>

                    <?php foreach ($users as $u): ?>

                        <option value="<?= esc($u['id']) ?>"
                            <?= ((int)$u['id'] === (int)$userId)
                                ? 'selected'
                                : '' ?>>

                            <?= esc(
                                $u['name']
                                ?? $u['username']
                                ?? $u['id']
                            ) ?>

                            <?php if (!empty($u['username'])): ?>
                                (<?= esc($u['username']) ?>)
                            <?php endif; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

        </div>

    </form>


    <?php if ($user): ?>

        <form action="<?= base_url('user-subject/store') ?>"
              method="post">

            <?= csrf_field() ?>

            <input type="hidden"
                   name="user_id"
                   value="<?= esc($user['id']) ?>">

            <input type="hidden"
                   name="division_id"
                   value="<?= esc($divisionId) ?>">


            <div class="mb-3">

                <strong>
                    <?= esc(
                        $user['name']
                        ?? $user['username']
                        ?? $user['id']
                    ) ?>
                </strong>

                <div class="text-muted">
                    Select subjects for this division.
                </div>

            </div>


            <div class="table-responsive"
                 style="
                    border-radius:12px;
                    overflow:hidden;
                    border:1px solid rgba(255,255,255,0.1);
                 ">

                <table class="table glass-table align-middle mb-0">

                    <thead>

                        <tr>
                            <th class="ps-3"
                                style="width:60px;">
                                #
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Subject Name
                            </th>

                            <th>
                                Division
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (empty($subjects)): ?>

                        <tr>
                            <td colspan="4"
                                class="text-center py-4">

                                No subjects found
                                for this division.

                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($subjects as $s): ?>

                            <tr>

                                <td class="ps-3">

                                    <input type="checkbox"
                                           class="form-check-input"
                                           name="subject_ids[]"
                                           value="<?= esc($s['id']) ?>"
                                        <?= in_array(
                                                (int)$s['id'],
                                                $assignedSubjectIds ?? [],
                                                true
                                            )
                                            ? 'checked'
                                            : '' ?>>

                                </td>


                                <td>

                                    <?= esc(
                                        $s['subject_code']
                                        ?? '-'
                                    ) ?>

                                </td>


                                <td>

                                    <div class="fw-bold text-dark">

                                        <?= esc(
                                            $s['subject_name']
                                            ?? $s['id']
                                        ) ?>

                                    </div>

                                    <?php if (!empty($s['description'])): ?>

                                        <small class="text-muted">

                                            <?= esc(
                                                $s['description']
                                            ) ?>

                                        </small>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?= esc(
                                        $s['division_name']
                                        ?? '-'
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>


            <?php if (!empty($subjects)): ?>

                <div class="d-flex justify-content-end mt-4">

                    <a href="<?= base_url('users') ?>"
                       class="btn btn-outline-secondary me-2">

                        Back

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        Save Changes

                    </button>

                </div>

            <?php endif; ?>

        </form>

    <?php endif; ?>

</div>

<?= $this->endSection() ?>