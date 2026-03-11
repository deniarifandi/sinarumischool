<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserSubjectModel;
use App\Models\UserModel;
use App\Models\SubjectModel;

class UserSubject extends BaseController
{
    protected $userSubjectModel;
    protected $userModel;
    protected $subjectModel;

    public function __construct()
    {
        $this->userSubjectModel = new UserSubjectModel();
        $this->userModel        = new UserModel();
        $this->subjectModel     = new SubjectModel();
    }

    public function index()
    {
        $userId = $this->request->getGet('user');
        $divisionId = $this->request->getGet('division');

        $builder = $this->userSubjectModel->builder();

        $builder->select('subjects.subject_name, users.name');
        $builder->join('users', 'users.id = user_subjects.user_id', 'left');
        $builder->join('subjects', 'subjects.id = user_subjects.subject_id', 'left');

        if ($userId) {
            $builder->where('user_subjects.user_id', $userId);
        }
        if ($divisionId) {
            $builder->where('subjects.division_id', $divisionId);
        }

        $rows = $builder->get()->getResultArray();

        $grouped = [];

        foreach ($rows as $row) {
            $subject = $row['subject_name'];

            if (!isset($grouped[$subject])) {
                $grouped[$subject] = [
                    'subject_name' => $subject,
                    'teacher' => []
                ];
            }

            $grouped[$subject]['teacher'][] = $row['name'];
        }

        //return $this->response->setJSON(array_values($grouped));

        return view('user_subject/index', [
            'userSubjects' => $grouped,
            'userId'       => $userId,
        ]);
    }

    public function delete($id)
    {
        $this->userSubjectModel->delete($id);
        return redirect()->back();
    }

    public function assign($userId)
    {
        $assigned = $this->userSubjectModel
            ->where('user_id', $userId)
            ->findAll();

        $assignedIds = array_column($assigned, 'subject_id');

        $divisionIdList = $this->userModel->getUserDetailData($userId);

        // print_r($divisionIdList);
        // exit();

        $subjects = $this->subjectModel->select('subjects.*, divisions.division_name')
                    ->join('divisions','divisions.id = subjects.division_id','left')
                    ->join('user_divisions', 'user_divisions.division_id = divisions.id', 'left')
                    ->where('user_divisions.user_id',$userId)
                    ->get()->getResultArray();
        // echo json_encode($subjects);
        // exit();

        return view('user_subject/assign', [
            'user'               => $this->userModel->find($userId),
            'subjects'           => $subjects,
            'assignedSubjectIds' => $assignedIds,
        ]);
    }

    public function store()
    {
        $userId     = $this->request->getPost('user_id');
        $subjectIds = $this->request->getPost('subject_ids') ?? [];

        $this->userSubjectModel
            ->where('user_id', $userId)
            ->delete();

        foreach ($subjectIds as $subjectId) {
            $this->userSubjectModel->insert([
                'user_id'    => $userId,
                'subject_id' => $subjectId,
            ]);
        }

        return redirect()->to('/user-subject/assign/' . $userId);
    }


}