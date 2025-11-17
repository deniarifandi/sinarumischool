<h2>Add Teaching Journal Entry</h2>

<form action="<?= base_url('journal/store') ?>" method="post">

    User ID:
    <input type="text" name="user_id" value="<?= session()->get('id') ?>"><br>

    Date:
    <input type="date" name="date" required><br>
<!-- 
    Time Start:
    <input type="time" name="time_start" required><br>

    Time End:
    <input type="time" name="time_end" required><br> -->

    Class:
    <select name="class_id" required>
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

    Sub-Chapter:
    <select name="subchapter_id" id="subchapter" required>
        <option value="">-- Select Sub-Chapter --</option>
    </select><br><br>

    Lesson Objectives:
    <select name="objective_id" id="objective">
        <option value="">-- Select Objective --</option>
    </select><br><br>

    Activities:<br>
    <textarea name="activities" required></textarea><br>

    Notes:<br>
    <textarea name="notes"></textarea><br><br>

    <button type="submit">Save</button>
</form>


<script>
document.getElementById('subject').addEventListener('change', function () {
    let subjectId = this.value;

    fetch('<?= base_url("ajax/chapters") ?>/' + subjectId)
        .then(res => res.json())
        .then(data => {
            let chapter = document.getElementById('chapter');
            chapter.innerHTML = '<option value="">-- Select Chapter --</option>';

            data.forEach(c => {
                chapter.innerHTML += `<option value="${c.id}">${c.chapter_name}</option>`;
            });

            // clear subchapters & objectives
            document.getElementById('subchapter').innerHTML = '<option value="">-- Select Sub-Chapter --</option>';
            document.getElementById('objective').innerHTML = '<option value="">-- Select Objective --</option>';
        });
});

// when chapter selected → load subchapters
document.getElementById('chapter').addEventListener('change', function () {
    let chapterId = this.value;

    fetch('<?= base_url("ajax/subchapters") ?>/' + chapterId)
        .then(res => res.json())
        .then(data => {
            let sub = document.getElementById('subchapter');
            sub.innerHTML = '<option value="">-- Select Sub-Chapter --</option>';

            data.forEach(s => {
                sub.innerHTML += `<option value="${s.id}">${s.sub_name}</option>`;
            });

            document.getElementById('objective').innerHTML = '<option value="">-- Select Objective --</option>';
        });
});

// when subchapter selected → load lesson objectives
document.getElementById('subchapter').addEventListener('change', function () {
    let subId = this.value;

    fetch('<?= base_url("ajax/objectives") ?>/' + subId)
        .then(res => res.json())
        .then(data => {
            let obj = document.getElementById('objective');
            obj.innerHTML = '<option value="">-- Select Objective --</option>';

            data.forEach(o => {
                obj.innerHTML += `<option value="${o.id}">${o.objective_text}</option>`;
            });
        });
});
</script>
