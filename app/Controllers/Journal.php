<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Journal extends BaseController
{
    /* =========================
        INDEX
    ========================== */
    public function index()
    {
        return view('journal/index');
    }

    /* =========================
        DATATABLE
    ========================== */
    public function datatable()
    {
        $db = \Config\Database::connect();

        $role      = session()->get('role');
        $userId    = session()->get('id');
        $divisions = session()->get('divisions') ?? [];

        $builder = $db->table('teaching_journals')
            ->select('
                teaching_journals.id,
                teaching_journals.date,
                classes.class_name,
                subjects.subject_name,
                teaching_journals.activities,
                teaching_journals.jpspend
            ')
            ->join('classes', 'classes.id = teaching_journals.class_id')
            ->join('subjects', 'subjects.id = teaching_journals.subject_id')
            ->whereIn('classes.division_id', $divisions);

        // Guru only sees own journal
        if ($role === 'guru') {
            $builder->where('teaching_journals.user_id', $userId);
        }

        return (new Datatable())->generate(
            $builder,
            'id',
            ['class_name', 'subject_name', 'activities'],
            ['date']
        );
    }

    /* =========================
        CREATE
    ========================== */
    public function create()
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $classes = $db->table('classes')
            ->whereIn('division_id', $divisions)
            ->get()->getResultArray();

        $subjects = $db->table('subjects')
            ->whereIn('division_id', $divisions)
            ->get()->getResultArray();

        return view('journal/create', [
            'classes'  => $classes,
            'subjects' => $subjects
        ]);
    }

    /* =========================
        STORE
    ========================== */
    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'user_id'       => session()->get('id'),
            'class_id'      => $this->request->getPost('class_id'),
            'subject_id'    => $this->request->getPost('subject_id'),
            'chapter_id'    => $this->request->getPost('chapter_id'),
            'subchapter_id' => $this->request->getPost('subchapter_id'),
            'date'          => $this->request->getPost('date'),
            'activities'    => $this->request->getPost('activities'),
            'notes'         => $this->request->getPost('notes'),
            'jpspend'       => $this->request->getPost('jpspend'),
        ];

        $db->table('teaching_journals')->insert($data);

        return redirect()
            ->to(base_url('journal'))
            ->with('success', 'Journal saved successfully');
    }

    /* =========================
        EDIT
    ========================== */
    public function edit($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $journal = $db->table('teaching_journals')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$journal) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Load dropdowns
        $classes = $db->table('classes')
            ->whereIn('division_id', $divisions)
            ->get()->getResultArray();

        $subjects = $db->table('subjects')
            ->whereIn('division_id', $divisions)
            ->get()->getResultArray();

        return view('journal/edit', [
            'journal'  => $journal,
            'classes'  => $classes,
            'subjects' => $subjects
        ]);
    }

    /* =========================
        UPDATE
    ========================== */
    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'class_id'      => $this->request->getPost('class_id'),
            'subject_id'    => $this->request->getPost('subject_id'),
            'chapter_id'    => $this->request->getPost('chapter_id'),
            'subchapter_id' => $this->request->getPost('subchapter_id'),
            'date'          => $this->request->getPost('date'),
            'activities'    => $this->request->getPost('activities'),
            'notes'         => $this->request->getPost('notes'),
            'jpspend'       => $this->request->getPost('jpspend'),
        ];

        $db->table('teaching_journals')->where('id', $id)->update($data);

        return redirect()
            ->to(base_url('journal'))
            ->with('success', 'Journal updated successfully');
    }

    /* =========================
        DELETE
    ========================== */
    public function delete($id)
    {
        $db = \Config\Database::connect();

        $db->table('teaching_journals')->where('id', $id)->delete();

        return redirect()
            ->to(base_url('journal'))
            ->with('success', 'Journal deleted successfully');
    }

    public function getChapters()
{
    $db = \Config\Database::connect();

    $subject_id = $this->request->getGet('subject_id');

    $chapters = $db->table('chapters')
        ->where('subject_id', $subject_id)
        ->orderBy('order_number', 'ASC')
        ->get()
        ->getResultArray();

    return $this->response->setJSON($chapters);
}

public function getSubchapters()
{
    $db = \Config\Database::connect();

    $chapter_id = $this->request->getGet('chapter_id');

    $subs = $db->table('sub_chapters')
        ->where('chapter_id', $chapter_id)
        ->orderBy('order_number', 'ASC')
        ->get()
        ->getResultArray();

    return $this->response->setJSON($subs);
}

}
