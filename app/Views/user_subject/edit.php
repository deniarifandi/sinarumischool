<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<div class="glass-card shadow-sm border-0 p-4">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold text-light">Manage Subject Users</h4>
            <div class="text-light small d-flex align-items-center gap-2">
                <span>
                    Subject:
                    <strong class="text-light">
                        <?= esc($subject['subject_name']) ?>
                    </strong>
                </span>

                <span>&bull;</span>

                <span>
                    Division:
                    <strong class="text-light">
                        <?= esc($subject['division_name'] ?? $subject['division_id']) ?>
                    </strong>
                </span>
            </div>
        </div>

        <div>
            <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                Selected:
                <strong id="selectedCount" class="text-primary">0</strong>
                users
            </span>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= esc(session()->getFlashdata('success')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= esc(session()->getFlashdata('error')) ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post"
          action="<?= base_url('user-subject/update/' . $subject['id']) ?>"
          id="userSubjectForm">

        <?= csrf_field() ?>

        <div class="table-responsive">
            <table id="userSubjectTable"
                   class="table table-hover align-middle w-100 border-top border-bottom">

                <thead class="table-light">
                    <tr>
                        <th style="width:50px;" class="text-center">
                            <input type="checkbox"
                                   id="checkAll"
                                   class="form-check-input"
                                   title="Select all on current page">
                        </th>

                        <th style="width:60px;">#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Position</th>
                        <th>NIP</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach ($users as $index => $user):

                    $userId = (int) $user['id'];

                    $isAssigned = in_array(
                        $userId,
                        $assignedUserIds ?? [],
                        true
                    );

                ?>

                    <tr class="<?= $isAssigned ? 'table-active' : '' ?>"
                        style="cursor:pointer;">

                        <td class="text-center"
                            data-order="<?= $isAssigned ? 1 : 0 ?>">

                            <input type="checkbox"
                                   value="<?= esc($userId) ?>"
                                   class="form-check-input user-checkbox"
                                   <?= $isAssigned ? 'checked' : '' ?>>

                        </td>

                        <td class="text-muted">
                            <?= $index + 1 ?>
                        </td>

                        <td>
                            <div class="fw-semibold text-dark">
                                <?= esc(
                                    $user['name']
                                    ?? $user['username']
                                    ?? $user['id']
                                ) ?>
                            </div>
                        </td>

                        <td>
                            <code>
                                <?= esc($user['username'] ?? '-') ?>
                            </code>
                        </td>

                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border">
                                <?= esc(
                                    $user['guru_jabatan']
                                    ?? $user['guru_role']
                                    ?? '-'
                                ) ?>
                            </span>
                        </td>

                        <td>
                            <?= esc($user['nip'] ?? '-') ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Hidden selected users will be generated here -->
        <div id="selectedUsersContainer"></div>

        <!-- Footer -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">

            <a href="<?= base_url(
                'user-subject?division=' . $subject['division_id']
            ) ?>"
               class="btn btn-outline-secondary px-4">

                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>

            <button type="submit"
                    class="btn btn-primary px-4 shadow-sm">

                <i class="bi bi-save me-1"></i>
                Save Changes

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SELECTED USERS STATE
    |--------------------------------------------------------------------------
    |
    | This is the important part.
    |
    | We store selected user IDs independently from DataTables DOM.
    | Therefore pagination/search will NOT lose the selection.
    |
    */

    const selectedUsers = new Set();

    /*
    |--------------------------------------------------------------------------
    | INITIAL ASSIGNED USERS
    |--------------------------------------------------------------------------
    */

    <?php foreach ($assignedUserIds ?? [] as $userId): ?>

        selectedUsers.add(
            String(<?= (int) $userId ?>)
        );

    <?php endforeach; ?>


    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table = $('#userSubjectTable').DataTable({

        pageLength: 25,

        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'All']
        ],

        order: [
            [0, 'desc'],
            [2, 'asc']
        ],

        columnDefs: [
            {
                orderable: false,
                targets: 0
            },
            {
                searchable: false,
                targets: [0, 1]
            }
        ],

        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search users..."
        }

    });


    /*
    |--------------------------------------------------------------------------
    | UPDATE COUNT
    |--------------------------------------------------------------------------
    */

    function updateSelectedCount() {

        document.getElementById('selectedCount').textContent =
            selectedUsers.size;

    }


    /*
    |--------------------------------------------------------------------------
    | SYNC CHECKBOXES WITH SELECTED STATE
    |--------------------------------------------------------------------------
    |
    | Every time DataTables changes page/search/sort,
    | checkbox state is restored from selectedUsers.
    |
    */

    function syncVisibleCheckboxes() {

        table
            .rows({
                page: 'current'
            })
            .nodes()
            .to$()
            .find('.user-checkbox')
            .each(function () {

                const userId = String(this.value);

                const selected = selectedUsers.has(userId);

                this.checked = selected;

                const row = $(this).closest('tr');

                row.attr(
                    'data-selected',
                    selected ? '1' : '0'
                );

                if (selected) {
                    row.addClass('table-active');
                } else {
                    row.removeClass('table-active');
                }

            });

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK ALL STATE
    |--------------------------------------------------------------------------
    */

    function updateCheckAllState() {

        const visibleCheckboxes =
            table
                .rows({
                    page: 'current'
                })
                .nodes()
                .to$()
                .find('.user-checkbox');

        const total =
            visibleCheckboxes.length;

        let checked = 0;

        visibleCheckboxes.each(function () {

            if (
                selectedUsers.has(
                    String(this.value)
                )
            ) {
                checked++;
            }

        });

        const checkAll =
            document.getElementById('checkAll');

        if (total === 0) {

            checkAll.checked = false;
            checkAll.indeterminate = false;

        } else if (checked === total) {

            checkAll.checked = true;
            checkAll.indeterminate = false;

        } else if (checked > 0) {

            checkAll.checked = false;
            checkAll.indeterminate = true;

        } else {

            checkAll.checked = false;
            checkAll.indeterminate = false;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX CHANGE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.user-checkbox',
        function (e) {

            e.stopPropagation();

            const userId =
                String(this.value);

            const row =
                $(this).closest('tr');


            if (this.checked) {

                selectedUsers.add(userId);

                row.addClass('table-active');

            } else {

                selectedUsers.delete(userId);

                row.removeClass('table-active');

            }


            row.attr(
                'data-selected',
                this.checked ? '1' : '0'
            );


            updateSelectedCount();

            updateCheckAllState();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ROW CLICK
    |--------------------------------------------------------------------------
    */

    $('#userSubjectTable tbody').on(
        'click',
        'tr',
        function (e) {

            if (
                $(e.target).is(
                    'input, label, a, button'
                )
            ) {
                return;
            }


            const checkbox =
                $(this).find('.user-checkbox');


            checkbox
                .prop(
                    'checked',
                    !checkbox.prop('checked')
                )
                .trigger('change');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHECK ALL CURRENT PAGE
    |--------------------------------------------------------------------------
    */

    $('#checkAll').on(
        'change',
        function () {

            const checked =
                this.checked;


            table
                .rows({
                    page: 'current'
                })
                .nodes()
                .to$()
                .find('.user-checkbox')
                .each(function () {

                    const userId =
                        String(this.value);


                    if (checked) {

                        selectedUsers.add(userId);

                    } else {

                        selectedUsers.delete(userId);

                    }

                });


            syncVisibleCheckboxes();

            updateSelectedCount();

            updateCheckAllState();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DATATABLE DRAW
    |--------------------------------------------------------------------------
    |
    | Triggered when:
    | - next page
    | - previous page
    | - search
    | - sort
    | - change page length
    |
    */

    table.on(
        'draw',
        function () {

            syncVisibleCheckboxes();

            updateCheckAllState();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | We DO NOT submit the visible checkboxes.
    |
    | Instead, we create hidden inputs for ALL selected users.
    |
    | So users from other pages/search results are still submitted.
    |
    */

    $('#userSubjectForm').on(
        'submit',
        function () {

            const container =
                document.getElementById(
                    'selectedUsersContainer'
                );


            // Clear previous hidden inputs

            container.innerHTML = '';


            // Create hidden input for every selected user

            selectedUsers.forEach(
                function (userId) {

                    const input =
                        document.createElement('input');

                    input.type = 'hidden';

                    input.name = 'user_ids[]';

                    input.value = userId;

                    container.appendChild(input);

                }
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE
    |--------------------------------------------------------------------------
    */

    syncVisibleCheckboxes();

    updateSelectedCount();

    updateCheckAllState();

});

</script>

<?= $this->endSection() ?>