<?php

namespace App\Controllers;

use App\Models\SubjectModel;

class SubjectController extends BaseController
{
    protected SubjectModel $subjectModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        $divisionId = (int) $this->request->getGet('division');

        $subjects = $divisionId
            ? $this->subjectModel->where('division_id', $divisionId)->findAll()
            : $this->subjectModel->findAll();

        return view('subject/index', [
            'subjects'   => $subjects,
            'divisionId' => $divisionId,
        ]);
    }

    public function create()
    {
        $divisionId = (int) $this->request->getGet('division');

        return view('subject/form', [
            'divisionId' => $divisionId,
        ]);
    }

    public function edit($id)
    {
        $divisionId = (int) $this->request->getGet('division');
        $subject = $this->subjectModel->find($id);

        if (!$subject || (int) $subject['division_id'] !== $divisionId) {
            return redirect()->to('subject?division=' . $divisionId)
                ->with('error', 'Subject not found');
        }

        return view('subject/form', [
            'subject'    => $subject,
            'divisionId' => $divisionId,
        ]);
    }

    public function save($id = null)
    {
        $divisionId = (int) $this->request->getPost('division_id');

        $data = [
            'division_id'  => $divisionId,
            'subject_code' => trim((string) $this->request->getPost('subject_code')),
            'subject_name' => trim((string) $this->request->getPost('subject_name')),
            'description'  => $this->request->getPost('description'),
        ];

        if ($data['subject_name'] === '') {
            return redirect()->back()->withInput()->with('error', 'Subject name is required');
        }

        if ($id) {
            $subject = $this->subjectModel->find($id);

            if (!$subject || (int) $subject['division_id'] !== $divisionId) {
                return redirect()->to('subject?division=' . $divisionId)
                    ->with('error', 'Invalid access');
            }

            $this->subjectModel->update($id, $data);
        } else {
            $this->subjectModel->insert($data);
        }

        return redirect()
            ->to('subject?division=' . $divisionId)
            ->with('success', 'Saved');
    }

    public function delete($id)
    {
        $divisionId = (int) $this->request->getPost('division_id');
        $subject = $this->subjectModel->find($id);

        if ($subject && (int) $subject['division_id'] === $divisionId) {
            $this->subjectModel->delete($id);
        }

        return redirect()
            ->to('subject?division=' . $divisionId)
            ->with('success', 'Deleted');
    }
}
