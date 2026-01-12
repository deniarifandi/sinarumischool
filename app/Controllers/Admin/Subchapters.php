<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Subchapters extends BaseController
{
    public function index($chapter_id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Validate chapter via subject division
        $chapter = $db->table('chapters')
            ->select('chapters.*, subjects.subject_name, subjects.division_id')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $chapter_id)
            ->whereIn('subjects.division_id', $divisions)
            ->get()
            ->getRowArray();

        if (!$chapter) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/subchapters/index', [
            'chapter' => $chapter
        ]);
    }

    public function datatable($chapter_id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Validate access
        $allowed = $db->table('chapters')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $chapter_id)
            ->whereIn('subjects.division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $builder = $db->table('sub_chapters')
            ->select('id, sub_code, sub_name, description, order_number')
            ->where('chapter_id', $chapter_id)
            ->orderBy('order_number', 'ASC');

        return (new Datatable())->generate(
            $builder,
            'id',
            ['sub_code', 'sub_name', 'description'],
            ['order_number', 'sub_code', 'sub_name']
        );
    }

    public function create($chapter_id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Validate chapter access
        $chapter = $db->table('chapters')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $chapter_id)
            ->whereIn('subjects.division_id', $divisions)
            ->select('chapters.*')
            ->get()
            ->getRowArray();

        if (!$chapter) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/subchapters/create', [
            'chapter' => $chapter
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $chapter_id = $this->request->getPost('chapter_id');

        // Validate chapter access
        $allowed = $db->table('chapters')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $chapter_id)
            ->whereIn('subjects.division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $data = [
            'chapter_id'   => $chapter_id,
            'sub_code'     => $this->request->getPost('sub_code'),
            'sub_name'     => $this->request->getPost('sub_name'),
            'description'  => $this->request->getPost('description'),
            'order_number' => $this->request->getPost('order_number'),
        ];

        $db->table('sub_chapters')->insert($data);

        return redirect()
            ->to(base_url('admin/subchapters/' . $chapter_id))
            ->with('success', 'Sub-chapter added successfully');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $sub = $db->table('sub_chapters')->where('id', $id)->get()->getRowArray();

        if (!$sub) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validate access via chapter → subject
        $allowed = $db->table('chapters')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $sub['chapter_id'])
            ->whereIn('subjects.division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        return view('admin/subchapters/edit', [
            'sub' => $sub
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $sub = $db->table('sub_chapters')->where('id', $id)->get()->getRowArray();

        if (!$sub) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validate access
        $allowed = $db->table('chapters')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $sub['chapter_id'])
            ->whereIn('subjects.division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $data = [
            'sub_code'     => $this->request->getPost('sub_code'),
            'sub_name'     => $this->request->getPost('sub_name'),
            'description'  => $this->request->getPost('description'),
            'order_number' => $this->request->getPost('order_number'),
        ];

        $db->table('sub_chapters')->where('id', $id)->update($data);

        return redirect()
            ->to(base_url('admin/subchapters/' . $sub['chapter_id']))
            ->with('success', 'Sub-chapter updated successfully');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $sub = $db->table('sub_chapters')->where('id', $id)->get()->getRowArray();

        if (!$sub) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validate access
        $allowed = $db->table('chapters')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('chapters.id', $sub['chapter_id'])
            ->whereIn('subjects.division_id', $divisions)
            ->countAllResults();

        if (!$allowed) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $db->table('sub_chapters')->where('id', $id)->delete();

        return redirect()
            ->to(base_url('admin/subchapters/' . $sub['chapter_id']))
            ->with('success', 'Sub-chapter deleted successfully');
    }
}
