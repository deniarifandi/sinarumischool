<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Chapters extends BaseController
{
    public function index($subject_id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Subject must belong to allowed division
        $subject = $db->table('subjects')
            ->where('id', $subject_id)
            ->whereIn('division_id', $divisions)
            ->get()
            ->getRowArray();

        if (!$subject) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/chapters/index', [
            'subject' => $subject
        ]);
    }

    public function datatable($subject_id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Validate subject access
        $subject = $db->table('subjects')
            ->where('id', $subject_id)
            ->whereIn('division_id', $divisions)
            ->countAllResults();

        if (!$subject) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $builder = $db->table('chapters')
            ->select('id, chapter_code,grade,chapter_name, description, order_number, jp')
            ->where('subject_id', $subject_id)
            ->orderBy('order_number', 'ASC');

        return (new Datatable())->generate(
            $builder,
            'id',
            ['chapter_code', 'chapter_name', 'description'],
            ['order_number', 'chapter_code', 'chapter_name']
        );
    }

    public function create($subject_id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Validate subject access
        $subject = $db->table('subjects')
            ->where('id', $subject_id)
            ->whereIn('division_id', $divisions)
            ->get()
            ->getRowArray();

        if (!$subject) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/chapters/create', [
            'subject' => $subject
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $subject_id = $this->request->getPost('subject_id');

        // Validate subject access
        $allowed = $db->table('subjects')
            ->where('id', $subject_id)
            ->whereIn('division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $data = [
            'subject_id'   => $subject_id,
            'chapter_code' => $this->request->getPost('chapter_code'),
            'grade'        => $this->request->getPost('grade'),
            'chapter_name' => $this->request->getPost('chapter_name'),
            'description'  => $this->request->getPost('description'),
            'order_number' => $this->request->getPost('order_number'),
            'jp' => $this->request->getPost('jp'),
        ];

        $db->table('chapters')->insert($data);

        return redirect()
            ->to(base_url('admin/chapters/' . $subject_id))
            ->with('success', 'Chapter added successfully');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $chapter = $db->table('chapters')
        ->select('chapters.*, subjects.subject_name')
        ->join('subjects','subjects.id = chapters.subject_id')
        ->where('chapters.id', $id)->get()->getRowArray();


        if (!$chapter) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validate subject access
        $allowed = $db->table('subjects')
            ->where('id', $chapter['subject_id'])
            ->whereIn('division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        return view('admin/chapters/edit', [
            'chapter' => $chapter
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $chapter = $db->table('chapters')->where('id', $id)->get()->getRowArray();

        if (!$chapter) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validate subject access
        $allowed = $db->table('subjects')
            ->where('id', $chapter['subject_id'])
            ->whereIn('division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $data = [
            'chapter_code' => $this->request->getPost('chapter_code'),
            'chapter_name' => $this->request->getPost('chapter_name'),
            'description'  => $this->request->getPost('description'),
            'order_number' => $this->request->getPost('order_number'),
            'grade' => $this->request->getPost('grade'),
            'jp' => $this->request->getPost('jp'),
        ];

        $db->table('chapters')->where('id', $id)->update($data);

        return redirect()
            ->to(base_url('admin/chapters/' . $chapter['subject_id']))
            ->with('success', 'Chapter updated successfully');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $chapter = $db->table('chapters')->where('id', $id)->get()->getRowArray();

        if (!$chapter) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validate subject access
        $allowed = $db->table('subjects')
            ->where('id', $chapter['subject_id'])
            ->whereIn('division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $db->table('chapters')->where('id', $id)->delete();

        return redirect()
            ->to(base_url('admin/chapters/' . $chapter['subject_id']))
            ->with('success', 'Chapter deleted successfully');
    }
}
