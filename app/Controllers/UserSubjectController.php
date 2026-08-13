<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SubjectModel;
use App\Models\DivisionModel;
use App\Models\UserSubjectModel;

class UserSubjectController extends BaseController
{
    protected $userModel;
    protected $subjectModel;
    protected $divisionModel;
    protected $userSubjectModel;

    public function __construct()
    {
        $this->userModel        = new UserModel();
        $this->subjectModel     = new SubjectModel();
        $this->divisionModel    = new DivisionModel();
        $this->userSubjectModel = new UserSubjectModel();
    }

    /**
     * Subject list with assigned users.
     *
     * /user-subject?division=3
     */
    public function index()
    {
        $divisionId = (int) $this->request->getGet('division');

        if (!$divisionId) {
            return redirect()
                ->to(base_url('users'))
                ->with('error', 'Division is required.');
        }

        $division = $this->divisionModel->find($divisionId);

        if (!$division) {
            return redirect()
                ->to(base_url('users'))
                ->with('error', 'Division not found.');
        }

        $rows = $this->userSubjectModel
            ->getSubjectsWithUsers($divisionId);

        $user_id = session('id') ?? session('user_id');
        $userDetail = $this->userModel->getUserDetailData($user_id);
        $userDetail = $userDetail[0] ?? null;


        /*
         * Convert flat query result into:
         *
         * [
         *     subject_id => [
         *         subject information,
         *         users => []
         *     ]
         * ]
         */
        $subjects = [];

        foreach ($rows as $row) {

            $subjectId = $row['subject_id'];

            if (!isset($subjects[$subjectId])) {

                $subjects[$subjectId] = [
                    'id'            => $subjectId,
                    'subject_code'  => $row['subject_code'],
                    'subject_name'  => $row['subject_name'],
                    'description'   => $row['description'],
                    'division_id'   => $row['division_id'],
                    'division_name' => $row['division_name'],
                    'users'         => [],
                ];
            }

            if (!empty($row['user_id'])) {

                $subjects[$subjectId]['users'][] = [
                    'id'          => $row['user_id'],
                    'name'        => $row['user_name'],
                    'username'    => $row['username'],
                ];
            }
        }

        return view('user_subject/index', [
            'division'   => $division,
            'divisionId' => $divisionId,
            'subjects'   => array_values($subjects),
            'userDetail'       => $userDetail
        ]);
    }

    /**
     * Edit users assigned to a subject.
     *
     * /user-subject/edit/12
     */
    public function edit($subjectId)
    {
        $subjectId = (int) $subjectId;

        $subject = $this->subjectModel
            ->select('
                subjects.*,
                divisions.division_name
            ')
            ->join(
                'divisions',
                'divisions.id = subjects.division_id',
                'left'
            )
            ->find($subjectId);

        if (!$subject) {
            return redirect()
                ->to(base_url('users'))
                ->with('error', 'Subject not found.');
        }

        // $users = $this->userModel
        //     ->orderBy('name', 'ASC')
        //     ->findAll();

         $users = $this->userModel
            ->select('users.*')
            ->orderBy('name', 'ASC')
            ->join('user_divisions','user_divisions.user_id = users.id')
            ->where('user_divisions.division_id',$subject['division_id'])
            ->where('users.deleted_at',null)
            ->findAll();

        $assignedUserIds =
            $this->userSubjectModel
                ->getAssignedUserIds($subjectId);

        return view('user_subject/edit', [
            'subject'         => $subject,
            'users'           => $users,
            'assignedUserIds' => $assignedUserIds,
        ]);
    }

    /**
     * Save users for a subject.
     */
    public function update($subjectId)
    {
        $subjectId = (int) $subjectId;

        $subject = $this->subjectModel->find($subjectId);

        if (!$subject) {
            return redirect()
                ->to(base_url('users'))
                ->with('error', 'Subject not found.');
        }

        $userIds = $this->request->getPost('user_ids') ?? [];

        $userIds = array_map('intval', $userIds);

        // Remove duplicates.
        $userIds = array_values(array_unique($userIds));

        $success = $this->userSubjectModel
            ->saveSubjectUsers($subjectId, $userIds);

        if (!$success) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Failed to save subject users.'
                );
        }

        return redirect()
            ->to(
                base_url(
                    'user-subject?division=' .
                    $subject['division_id']
                )
            )
            ->with(
                'success',
                'Subject users updated successfully.'
            );
    }
}