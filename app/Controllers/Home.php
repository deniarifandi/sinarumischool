<?php

namespace App\Controllers;

use App\Models\PresenceModel;
use App\Models\UserDivisionModel;
use App\Models\UserModel;
use App\Models\UserSubjectModel;
use DB;
class Home extends BaseController
{

    public function __construct()
    {    
        $this->presence = new PresenceModel();
        $this->userDivision = new UserDivisionModel();
        $this->userModel = new UserModel();
        $this->UserSubjectModel = new UserSubjectModel();

    }

   public function index(): string
    {
        $user_id = session('id') ?? session('user_id');

        $checkedToday = $this->presence->presence_check($user_id);
        $divisions    = $this->userDivision->getUserDivisions($user_id);

        $userDetail = $this->userModel->getUserDetailData($user_id);
        $userDetail = $userDetail[0] ?? null;

        if (!$userDetail) {
            throw new \RuntimeException('User not found');
        }

        $mainClass = $this->userModel->getUserMainClass($user_id);
        $mainClass = $mainClass[0] ?? null;

        $userSubjects = $this->UserSubjectModel->getUserSubjects($user_id);

        $allowedRoles = ['superadmin', 'teacher', 'teacher_admin'];

        $groupedSubjects = [];

        if (in_array($userDetail['role'], $allowedRoles) && !empty($userSubjects)) {
            foreach ($userSubjects as $subject) {
                $groupedSubjects[$subject['division_name']][] = $subject;
            }
        }

        $attendanceMissing = false;

        if ($mainClass) {
            $db = \Config\Database::connect();

            $hasAttendance = $db->table('absensi a')
                ->join('students s', 's.id = a.murid_id')
                ->where('s.class_id', $mainClass['id'])
                ->where('a.tanggal', date('Y-m-d'))
                ->countAllResults();

            $attendanceMissing = ($hasAttendance == 0);
        }

        return view('dashboard', [
            'checkedToday'      => $checkedToday,
            'divisions'         => $divisions,
            'user'              => $userDetail,
            'mainClass'         => $mainClass,
            'userSubjects'      => $userSubjects,
            'allowedRoles'      => $allowedRoles,
            'groupedSubjects'   => $groupedSubjects,
            'attendanceMissing' => $attendanceMissing,
        ]);
    }

    public function whatsappWebhook()
{
    // ==========================================
    // GET: Meta webhook verification
    // ==========================================

    if ($this->request->getMethod() === 'GET') {

        $mode      = $_GET['hub_mode'] ?? null;
        $token     = $_GET['hub_verify_token'] ?? null;
        $challenge = $_GET['hub_challenge'] ?? null;

        $verifyToken = 'sinarumi_whatsapp_webhook_8f92Kx2026';

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $this->response
                ->setStatusCode(200)
                ->setBody($challenge);
        }

        return $this->response
            ->setStatusCode(403)
            ->setBody('Verification failed');
    }


    // ==========================================
    // POST: WhatsApp webhook
    // ==========================================

    $rawData = file_get_contents('php://input');

    $data = json_decode($rawData, true);

    if (!$data) {
        return $this->response
            ->setStatusCode(400)
            ->setBody('Invalid JSON');
    }


    // ==========================================
    // Get message
    // ==========================================

    $messageData =
        $data['entry'][0]['changes'][0]['value']['messages'][0]
        ?? null;

    if (!$messageData) {
        return $this->response
            ->setStatusCode(200)
            ->setBody('No message');
    }


    // ==========================================
    // Only process text messages
    // ==========================================

    if (($messageData['type'] ?? null) !== 'text') {
        return $this->response
            ->setStatusCode(200)
            ->setBody('Not a text message');
    }


    $phone   = $messageData['from'] ?? null;
    $message = trim($messageData['text']['body'] ?? '');

    if (!$phone || !$message) {
        return $this->response
            ->setStatusCode(200)
            ->setBody('Missing sender or message');
    }


    // ==========================================
    // Database
    // ==========================================

    $db = \Config\Database::connect();


    // ==========================================
    // Only process "absen"
    // ==========================================

    if (strtolower($message) !== 'absen') {

        $responseText = 'Command not recognized';

        $db->table('absen_wa_test')->insert([
            'user_id'  => null,
            'phone'    => $phone,
            'message'  => $message,
            'response' => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Find user
    // ==========================================

    $user = $db->table('users')
        ->select('id, name, phone')
        ->where('phone', $phone)
        ->where('deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();


    // ==========================================
    // User not found
    // ==========================================

    if (!$user) {

        $responseText = 'User not registered';

        $db->table('absen_wa_test')->insert([
            'user_id'  => null,
            'phone'    => $phone,
            'message'  => $message,
            'response' => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Check today's attendance
    // ==========================================

    $today = date('Y-m-d');

    $existing = $db->table('presensidata')
        ->where('guru_id', $user['id'])
        ->where('presensidata_tanggal', $today)
        ->where('deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();


    // ==========================================
    // Already attended
    // ==========================================

    if ($existing) {

        $responseText =
            'Already attended today: ' . $user['name'];

        $db->table('absen_wa_test')->insert([
            'user_id'  => $user['id'],
            'phone'    => $phone,
            'message'  => $message,
            'response' => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Insert attendance
    // ==========================================

    $db->table('presensidata')->insert([
        'guru_id'              => $user['id'],
        'longitude'            => null,
        'latitude'             => null,
        'address'              => null,
        'presensidata_tanggal' => $today,
        'status'               => 1,
        'created_at'            => date('Y-m-d H:i:s')
    ]);


    // ==========================================
    // Success
    // ==========================================

    $responseText =
        'Attendance recorded successfully for ' . $user['name'];


    // ==========================================
    // Log response
    // ==========================================

    $db->table('absen_wa_test')->insert([
        'user_id'  => $user['id'],
        'phone'    => $phone,
        'message'  => $message,
        'response' => $responseText
    ]);


    return $this->response
        ->setStatusCode(200)
        ->setBody($responseText);
}


        public function whatsappWebhook22()
{
    // =========================
    // GET = Meta Webhook Verification
    // =========================
    if ($this->request->getMethod() === 'GET') {

        $mode      = $this->request->getGet('hub.mode');
        $token     = $this->request->getGet('hub.verify_token');
        $challenge = $this->request->getGet('hub.challenge');

        $verifyToken = 'sinarumi_whatsapp_webhook_8f92Kx2026';

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $this->response
                ->setStatusCode(200)
                ->setBody($challenge);
        }

        return $this->response
            ->setStatusCode(403)
            ->setBody('Verification failed');
    }


    // =========================
    // POST = WhatsApp Webhook
    // =========================

    $rawData = file_get_contents('php://input');

    $data = json_decode($rawData, true);

    if (!$data) {
        return $this->response
            ->setStatusCode(400)
            ->setJSON([
                'success' => false,
                'message' => 'Invalid JSON'
            ]);
    }

    // Temporary: save the entire incoming JSON
    $db = \Config\Database::connect();

    $db->table('absen_wa_test')->insert([
        'message' => $rawData
    ]);

    return $this->response
        ->setStatusCode(200)
        ->setJSON([
            'success' => true
        ]);
}
 }
