<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Objectives extends BaseController
{
    public function index($subchapter_id)
    {
        $db = \Config\Database::connect();

        // Context (subchapter → chapter → subject)
        $context = $db->table('sub_chapters')
            ->select('
                sub_chapters.*,
                chapters.chapter_name,
                subjects.subject_name
            ')
            ->join('chapters', 'chapters.id = sub_chapters.chapter_id')
            ->join('subjects', 'subjects.id = chapters.subject_id')
            ->where('sub_chapters.id', $subchapter_id)
            ->get()
            ->getRowArray();

        if (!$context) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/objectives/index', [
            'sub'     => $context,
            'chapter' => ['chapter_name' => $context['chapter_name']],
            'subject' => ['subject_name' => $context['subject_name']],
        ]);
    }

    public function datatable($subchapter_id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('lesson_objectives')
            ->select('id, objective_code, objective_text, order_number')
            ->where('subchapter_id', $subchapter_id)
            ->orderBy('order_number', 'ASC');

        return (new Datatable())->generate(
            $builder,
            'id',
            ['objective_code', 'objective_text'],
            ['order_number', 'objective_code']
        );
    }

    public function create($subchapter_id)
    {
        return view('admin/objectives/create', [
            'subchapter_id' => $subchapter_id
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'subchapter_id' => $this->request->getPost('subchapter_id'),
            'objective_code'=> $this->request->getPost('objective_code'),
            'objective_text'=> $this->request->getPost('objective_text'),
            'order_number'  => $this->request->getPost('order_number'),
        ];

        $db->table('lesson_objectives')->insert($data);

        return redirect()
            ->to(base_url('admin/objectives/'.$data['subchapter_id']))
            ->with('success', 'Objective added successfully');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $objective = $db->table('lesson_objectives')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$objective) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/objectives/edit', [
            'objective' => $objective
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $objective = $db->table('lesson_objectives')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        $data = [
            'objective_code'=> $this->request->getPost('objective_code'),
            'objective_text'=> $this->request->getPost('objective_text'),
            'order_number'  => $this->request->getPost('order_number'),
        ];

        $db->table('lesson_objectives')->where('id', $id)->update($data);

        return redirect()
            ->to(base_url('admin/objectives/'.$objective['subchapter_id']))
            ->with('success', 'Objective updated successfully');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $objective = $db->table('lesson_objectives')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        $db->table('lesson_objectives')->where('id', $id)->delete();

        return redirect()
            ->to(base_url('admin/objectives/'.$objective['subchapter_id']))
            ->with('success', 'Objective deleted successfully');
    }
}
