<h2>Input Gradebook</h2>

<form method="get" action="">
    Class:
    <select name="class_id" id="class" required>
        <option value="">-- Select Class --</option>
        <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
        <?php endforeach ?>
    </select><br><br>

    Subject:
    <select name="subject_id" id="subject" required>
        <option value="">-- Select Subject --</option>
        <?php foreach ($subjects as $s): ?>
            <option value="<?= $s['id'] ?>"><?= $s['subject_name'] ?></option>
        <?php endforeach ?>
    </select><br><br>

    Chapter:
    <select name="chapter_id" id="chapter" required>
        <option value="">-- Select Chapter --</option>
    </select><br><br>

    Subchapter:
    <select name="subchapter_id" id="subchapter" required>
        <option value="">-- Select Subchapter --</option>
    </select><br><br>

    Objective:
    <select name="objective_id" id="objective" required>
        <option value="">-- Select Objective --</option>
    </select><br><br>

    <button type="button" id="loadStudents">Load Students</button>
</form>

<div id="studentList"></div>

<script>
// Subject → Chapter
document.getElementById('subject').addEventListener('change', function() {
    fetch('<?= base_url("ajax/chapters") ?>/' + this.value)
        .then(r => r.json())
        .then(data => {
            let chapter = document.getElementById('chapter');
            chapter.innerHTML = '<option value="">-- Select Chapter --</option>';
            data.forEach(c => chapter.innerHTML += `<option value="${c.id}">${c.chapter_name}</option>`);
        });
});

// Chapter → Subchapter
document.getElementById('chapter').addEventListener('change', function() {
    fetch('<?= base_url("ajax/subchapters") ?>/' + this.value)
        .then(r => r.json())
        .then(data => {
            let sc = document.getElementById('subchapter');
            sc.innerHTML = '<option value="">-- Select Subchapter --</option>';
            data.forEach(s => sc.innerHTML += `<option value="${s.id}">${s.sub_name}</option>`);
        });
});

// Subchapter → Objective
document.getElementById('subchapter').addEventListener('change', function() {
    fetch('<?= base_url("ajax/objectives") ?>/' + this.value)
        .then(r => r.json())
        .then(data => {
            let obj = document.getElementById('objective');
            obj.innerHTML = '<option value="">-- Select Objective --</option>';
            data.forEach(o => obj.innerHTML += `<option value="${o.id}">${o.objective_text}</option>`);
        });
});

// Load Students
document.getElementById('loadStudents').addEventListener('click', function () {
    let classId = document.getElementById('class').value;
    fetch('<?= base_url("ajax/students") ?>/' + classId)
        .then(r => r.json())
        .then(data => {
            let html = `
            <form action="<?= base_url('gradebook/save') ?>" method="post">
                <input type="hidden" name="class_id" value="${classId}">
                <input type="hidden" name="subject_id" value="${document.getElementById('subject').value}">
                <input type="hidden" name="chapter_id" value="${document.getElementById('chapter').value}">
                <input type="hidden" name="subchapter_id" value="${document.getElementById('subchapter').value}">
                <input type="hidden" name="objective_id" value="${document.getElementById('objective').value}">
                <table border="1" cellpadding="8">
                    <tr><th>Student</th><th>Score</th></tr>
            `;

            data.forEach(s => {
                html += `
                    <tr>
                        <td>${s.name}</td>
                        <td>
                            <input type="hidden" name="student_id[]" value="${s.id}">
                            <input type="number" name="score[]" min="0" max="100">
                        </td>
                    </tr>
                `;
            });

            html += `</table><br><button type="submit">Save Grades</button></form>`;

            document.getElementById('studentList').innerHTML = html;
        });
});
</script>
