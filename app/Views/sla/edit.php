<?= $this->extend('main') ?>

<?= $this->section('content') ?>

<div class="card shadow-sm border-0">

    <!-- HEADER -->
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

        <h5 class="mb-0 text-primary fw-bold">
            <i class="bi bi-person-exclamation me-2"></i>
            Edit Student Late Arrival Slip
        </h5>

        <a href="<?= base_url('sla?division=' . $division_id) ?>"
           class="btn btn-outline-danger btn-sm align-self-start align-self-sm-center">

            <i class="bi bi-arrow-left"></i>
            Back

        </a>

    </div>


    <!-- BODY -->
    <div class="card-body p-3 p-sm-4">


        <!-- VALIDATION ERRORS -->
        <?php if (session()->getFlashdata('errors')): ?>

            <div class="alert alert-danger shadow-sm">

                <div class="fw-semibold mb-2">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Please correct the following errors:
                </div>

                <ul class="mb-0">

                    <?php foreach (session()->getFlashdata('errors') as $error): ?>

                        <li>
                            <?= esc($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <!-- GENERAL ERROR -->
        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle me-2"></i>

                <?= esc(session()->getFlashdata('error')) ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>


        <!-- FORM -->
        <form action="<?= base_url('sla/update/' . $sla['id']) ?>"
              method="post">

            <?= csrf_field() ?>

            <!-- Division -->
            <input type="hidden"
                   name="division_id"
                   value="<?= esc($division_id) ?>">


            <!-- CLASS & STUDENT -->
            <div class="row">

                <!-- CLASS -->
                <div class="col-md-6 mb-3">

                    <label for="class_id"
                           class="form-label fw-semibold">

                        Class
                        <span class="text-danger">*</span>

                    </label>

                    <?php
                    $selectedClassId = old(
                        'class_id',
                        $sla['class_id'] ?? ''
                    );
                    ?>

                    <select name="class_id"
                            id="class_id"
                            class="form-select"
                            required>

                        <option value="">
                            -- Select Class --
                        </option>

                        <?php if (!empty($classes)): ?>

                            <?php foreach ($classes as $class): ?>

                                <option value="<?= esc($class['id']) ?>"
                                    <?= (string) $selectedClassId === (string) $class['id']
                                        ? 'selected'
                                        : '' ?>>

                                    <?= esc($class['class_name']) ?>

                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value=""
                                    disabled>

                                No classes found

                            </option>

                        <?php endif; ?>

                    </select>

                </div>


                <!-- STUDENT -->
                <div class="col-md-6 mb-3">

                    <label for="student_id"
                           class="form-label fw-semibold">

                        Student
                        <span class="text-danger">*</span>

                    </label>

                    <?php
                    $selectedStudentId = old(
                        'student_id',
                        $sla['student_id'] ?? ''
                    );
                    ?>

                    <select name="student_id"
                            id="student_id"
                            class="form-select"
                            required
                            disabled>

                        <option value="">
                            -- Loading students... --
                        </option>

                    </select>

                    <div id="studentLoading"
                         class="form-text text-primary d-none">

                        <i class="bi bi-hourglass-split"></i>
                        Loading students...

                    </div>

                </div>

            </div>


            <!-- ARRIVAL TIME & POINT -->
            <div class="row">

                <!-- ARRIVAL TIME -->
                <div class="col-md-6 mb-3">

                    <label for="arrivaltime"
                           class="form-label fw-semibold">

                        Arrival Time
                        <span class="text-danger">*</span>

                    </label>

                    <?php
                    $arrivalTime = old(
                        'arrivaltime',
                        $sla['arrivaltime'] ?? ''
                    );

                    if (!empty($arrivalTime)) {

                        $arrivalTimestamp = strtotime($arrivalTime);

                        if ($arrivalTimestamp !== false) {

                            $arrivalTime = date(
                                'Y-m-d\TH:i',
                                $arrivalTimestamp
                            );

                        }

                    }
                    ?>

                    <input type="datetime-local"
                           name="arrivaltime"
                           id="arrivaltime"
                           class="form-control"
                           value="<?= esc($arrivalTime) ?>"
                           required>

                </div>


                <!-- POINT REDUCTION -->
                <div class="col-md-6 mb-3">

                    <label for="reduction"
                           class="form-label fw-semibold">

                        Point Reduction
                        <span class="text-danger">*</span>

                    </label>

                    <input type="number"
                           name="reduction"
                           id="reduction"
                           class="form-control"
                           value="<?= esc(
                               old(
                                   'reduction',
                                   $sla['reduction'] ?? 5
                               )
                           ) ?>"
                           min="0"
                           step="1"
                           required>

                </div>

            </div>


            <!-- PROBLEM -->
            <div class="mb-3">

                <label for="problem"
                       class="form-label fw-semibold">

                    Problem
                    <span class="text-danger">*</span>

                </label>

                <input type="text"
                       name="problem"
                       id="problem"
                       class="form-control"
                       value="<?= esc(
                           old(
                               'problem',
                               $sla['problem'] ?? 'Coming Late'
                           )
                       ) ?>"
                       required>

            </div>


            <!-- REASON -->
            <div class="mb-4">

                <label for="reason"
                       class="form-label fw-semibold">

                    Reason
                    <span class="text-danger">*</span>

                </label>

                <textarea name="reason"
                          id="reason"
                          class="form-control"
                          rows="3"
                          placeholder="Briefly explain the reason for being late..."
                          required><?= esc(
                              old(
                                  'reason',
                                  $sla['reason'] ?? ''
                              )
                          ) ?></textarea>

            </div>


            <hr class="text-muted">


            <!-- ACTION BUTTONS -->
            <div class="d-grid d-md-flex justify-content-md-end gap-2 mt-4">

                <!-- CANCEL -->
                <a href="<?= base_url('sla?division=' . $division_id) ?>"
                   class="btn btn-light border order-2 order-md-1">

                    <i class="bi bi-x-lg me-1"></i>
                    Cancel

                </a>


                <!-- UPDATE -->
                <button type="submit"
                        class="btn btn-primary order-1 order-md-2">

                    <i class="bi bi-save me-1"></i>
                    Update Late Arrival Slip

                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');
    const studentLoading = document.getElementById('studentLoading');

    // Existing student or student selected before validation error
    const selectedStudentId =
        <?= json_encode((string) $selectedStudentId) ?>;


    /**
     * Load students based on selected class and division
     */
    function loadStudents(
        classId,
        selectedStudentId = ''
    ) {

        // Clear student dropdown
        studentSelect.innerHTML = '';


        // No class selected
        if (!classId) {

            studentSelect.disabled = true;

            const option =
                document.createElement('option');

            option.value = '';

            option.textContent =
                '-- Select class first --';

            studentSelect.appendChild(option);

            studentLoading.classList.add(
                'd-none'
            );

            return;

        }


        // Loading state
        studentSelect.disabled = true;

        studentSelect.innerHTML = `
            <option value="">
                Loading students...
            </option>
        `;

        studentLoading.classList.remove(
            'd-none'
        );


        // AJAX URL
        const url =
            '<?= base_url('sla/students-by-class') ?>/'
            + encodeURIComponent(classId)
            + '?division='
            + encodeURIComponent(
                <?= json_encode((string) $division_id) ?>
            );


        fetch(url)

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        'Failed to load students'
                    );

                }

                return response.json();

            })


            .then(data => {

                studentSelect.innerHTML = '';


                // No students found
                if (
                    !Array.isArray(data) ||
                    data.length === 0
                ) {

                    const option =
                        document.createElement('option');

                    option.value = '';

                    option.textContent =
                        '-- No students found --';

                    studentSelect.appendChild(
                        option
                    );

                    studentSelect.disabled = true;

                    return;

                }


                // Default option
                const defaultOption =
                    document.createElement('option');

                defaultOption.value = '';

                defaultOption.textContent =
                    '-- Select Student --';

                studentSelect.appendChild(
                    defaultOption
                );


                // Add students
                data.forEach(student => {

                    const option =
                        document.createElement('option');

                    option.value =
                        student.id;

                    option.textContent =
                        student.name +
                        (
                            student.student_code
                                ? ' (' +
                                  student.student_code +
                                  ')'
                                : ''
                        );


                    // Select existing student
                    // or old student after validation error
                    if (
                        String(student.id) ===
                        String(selectedStudentId)
                    ) {

                        option.selected = true;

                    }


                    studentSelect.appendChild(
                        option
                    );

                });


                studentSelect.disabled = false;

            })


            .catch(error => {

                console.error(error);

                studentSelect.innerHTML = `
                    <option value="">
                        -- Failed to load students --
                    </option>
                `;

                studentSelect.disabled = true;

            })


            .finally(() => {

                studentLoading.classList.add(
                    'd-none'
                );

            });

    }


    /**
     * When class is changed manually
     */
    classSelect.addEventListener(
        'change',
        function () {

            // Clear student selection
            // because class has changed
            loadStudents(
                this.value,
                ''
            );

        }
    );


    /**
     * Load students automatically
     * when edit page opens.
     *
     * This automatically selects
     * the existing student.
     */
    const currentClassId =
        classSelect.value;


    if (currentClassId) {

        loadStudents(
            currentClassId,
            selectedStudentId
        );

    }

});
</script>


<?= $this->endSection() ?>