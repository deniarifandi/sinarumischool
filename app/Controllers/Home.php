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
    // GET: Meta Webhook Verification
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
    // POST: WhatsApp Webhook
    // ==========================================

    $rawData = file_get_contents('php://input');

    $data = json_decode($rawData, true);

    if (!$data) {
        return $this->response
            ->setStatusCode(400)
            ->setBody('Invalid JSON');
    }


    // ==========================================
    // Get WhatsApp Message
    // ==========================================

    $messageData =
        $data['entry'][0]['changes'][0]['value']['messages'][0]
        ?? null;

    /*
     * WhatsApp can also send webhook events
     * that don't contain a message.
     */
    if (!$messageData) {
        return $this->response
            ->setStatusCode(200)
            ->setBody('No message');
    }


    // ==========================================
    // Sender
    // ==========================================

    $phone = $messageData['from'] ?? null;

    if (!$phone) {
        return $this->response
            ->setStatusCode(200)
            ->setBody('No sender');
    }


    // Remove +, spaces, -, etc.
    $phone = preg_replace('/[^0-9]/', '', $phone);


    // ==========================================
    // Normalize Indonesian Phone Number
    // ==========================================

    /*
     * WhatsApp:
     * 628123456789
     *
     * Database:
     * 08123456789
     */

    $phoneSearch = $phone;

    if (str_starts_with($phone, '62')) {
        $phoneSearch = '0' . substr($phone, 2);
    }


    // ==========================================
    // Database
    // ==========================================

    $db = \Config\Database::connect();


    // ==========================================
    // Find User
    // ==========================================

    $user = $db->table('users')
        ->select('id, name, phone')
        ->where('phone', $phoneSearch)
        ->where('deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();


    // ==========================================
    // User Not Found
    // ==========================================

    if (!$user) {

        $responseText =
            'Nomor WhatsApp Anda belum terdaftar.' .
            "\n\n" .
            'Nomor yang diterima: ' . $phone .
            "\n" .
            'Nomor yang dicari: ' . $phoneSearch;

        $db->table('absen_wa_test')->insert([
            'user_id'      => null,
            'phone'        => $phone,
            'message_type' => $messageData['type'] ?? null,
            'latitude'     => null,
            'longitude'    => null,
            'status'       => null,
            'message'      => $messageData['text']['body']
                ?? '[location]',
            'response'     => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Message Type
    // ==========================================

    $messageType = $messageData['type'] ?? null;


    // =====================================================
    // CASE 1: TEXT MESSAGE
    // =====================================================

    if ($messageType === 'text') {

        $message = strtolower(trim(
            $messageData['text']['body'] ?? ''
        ));


        // ==========================================
        // Attendance Status
        // ==========================================

        $statusMap = [
            'absen' => 1, // Hadir
            'izin'  => 2, // Izin
            'sakit' => 3, // Sakit
        ];


        // ==========================================
        // Invalid Command
        // ==========================================

        if (!isset($statusMap[$message])) {

            $responseText =
                'Perintah tidak dikenali.' .
                "\n\n" .
                'Gunakan:' .
                "\n" .
                'absen = Hadir' .
                "\n" .
                'izin = Izin' .
                "\n" .
                'sakit = Sakit';

            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'text',
                'latitude'     => null,
                'longitude'    => null,
                'status'       => null,
                'message'      => $message,
                'response'     => $responseText
            ]);

            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Get Requested Status
        // ==========================================

        $status = $statusMap[$message];


        // ==========================================
        // Check Today's Attendance
        // ==========================================

        $today = date('Y-m-d');

        $existing = $db->table('presensidata')
            ->where('guru_id', $user['id'])
            ->where('presensidata_tanggal', $today)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();


        // ==========================================
        // Already Attended
        // ==========================================

        if ($existing) {

            $existingStatus = (int) $existing['status'];

            $statusNames = [
                1 => 'Hadir',
                2 => 'Izin',
                3 => 'Sakit'
            ];

            $existingStatusName =
                $statusNames[$existingStatus]
                ?? 'Unknown';


            $responseText =
                'Anda sudah melakukan absensi hari ini.' .
                "\n\n" .
                'Status: ' . $existingStatusName;


            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'text',
                'latitude'     => null,
                'longitude'    => null,
                'status'       => $status,
                'message'      => $message,
                'response'     => $responseText
            ]);


            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Ask User to Send Location
        // ==========================================

        $statusNames = [
            1 => 'Hadir',
            2 => 'Izin',
            3 => 'Sakit'
        ];

        $statusName = $statusNames[$status];


        $responseText =
            'Halo ' . $user['name'] . '.' .
            "\n\n" .
            'Status absensi: ' . $statusName .
            "\n\n" .
            'Silakan kirim lokasi Anda melalui WhatsApp untuk melanjutkan absensi.';


        // ==========================================
        // Save Waiting State
        // ==========================================

        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'text',
            'latitude'     => null,
            'longitude'    => null,
            'status'       => $status,
            'message'      => $message,
            'response'     => 'WAITING_LOCATION'
        ]);


        /*
         * IMPORTANT:
         *
         * This currently only returns the response
         * to Meta.
         *
         * It does NOT send a WhatsApp message to the user.
         *
         * WhatsApp Cloud API sending will be added separately.
         */


        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // =====================================================
    // CASE 2: LOCATION MESSAGE
    // =====================================================

    if ($messageType === 'location') {

        $latitude =
            $messageData['location']['latitude']
            ?? null;

        $longitude =
            $messageData['location']['longitude']
            ?? null;


        // ==========================================
        // Invalid Location
        // ==========================================

        if ($latitude === null || $longitude === null) {

            $responseText =
                'Lokasi tidak dapat dibaca.' .
                "\n\n" .
                'Silakan kirim lokasi Anda kembali.';


            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'location',
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'status'       => null,
                'message'      => '[location]',
                'response'     => $responseText
            ]);


            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Find Waiting Attendance
        // ==========================================

        $waiting = $db->table('absen_wa_test')
            ->where('user_id', $user['id'])
            ->where('phone', $phone)
            ->where('response', 'WAITING_LOCATION')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();


        // ==========================================
        // No Waiting Attendance
        // ==========================================

        if (!$waiting) {

            $responseText =
                'Tidak ada proses absensi yang sedang menunggu lokasi.' .
                "\n\n" .
                'Ketik "absen", "izin", atau "sakit" terlebih dahulu.';


            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'location',
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'status'       => null,
                'message'      => '[location]',
                'response'     => $responseText
            ]);


            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Get Requested Attendance Status
        // ==========================================

        $status = (int) $waiting['status'];


        // ==========================================
        // School Location
        // ==========================================

        /*
         * CHANGE THESE COORDINATES
         * to your actual school coordinates.
         */

        $schoolLatitude  = -7.9702370;
        $schoolLongitude = 112.6030000;


        // Maximum allowed distance in meters
        $allowedRadius = 100;


        // ==========================================
        // Calculate Distance
        // Haversine Formula
        // ==========================================

        $earthRadius = 6371000;

        $latFrom = deg2rad($schoolLatitude);
        $latTo   = deg2rad($latitude);

        $latDelta =
            deg2rad($latitude - $schoolLatitude);

        $lonDelta =
            deg2rad($longitude - $schoolLongitude);

        $a =
            sin($latDelta / 2) ** 2 +
            cos($latFrom) *
            cos($latTo) *
            sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        $distance = $earthRadius * $c;


        // ==========================================
        // Location Too Far
        // ==========================================

        if ($distance > $allowedRadius) {

            $responseText =
                'Absensi ditolak.' .
                "\n\n" .
                'Jarak Anda dari sekolah: ' .
                round($distance) .
                ' meter.' .
                "\n" .
                'Batas maksimal: ' .
                $allowedRadius .
                ' meter.';


            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'location',
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'status'       => $status,
                'message'      => '[location]',
                'response'     => $responseText
            ]);


            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Check Today's Attendance Again
        // ==========================================

        $today = date('Y-m-d');

        $existing = $db->table('presensidata')
            ->where('guru_id', $user['id'])
            ->where('presensidata_tanggal', $today)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();


        if ($existing) {

            $responseText =
                'Anda sudah melakukan absensi hari ini.';


            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'location',
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'status'       => $status,
                'message'      => '[location]',
                'response'     => $responseText
            ]);


            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Insert Attendance
        // ==========================================

        $insertData = [
            'guru_id'              => $user['id'],
            'longitude'            => $longitude,
            'latitude'             => $latitude,
            'address'              => null,
            'presensidata_tanggal' => $today,
            'status'               => $status,
            'created_at'           => date('Y-m-d H:i:s')
        ];


        $inserted = $db->table('presensidata')
            ->insert($insertData);


        // ==========================================
        // Insert Failed
        // ==========================================

        if (!$inserted) {

            $responseText =
                'Absensi gagal disimpan ke database.';


            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => 'location',
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'status'       => $status,
                'message'      => '[location]',
                'response'     => $responseText
            ]);


            return $this->response
                ->setStatusCode(200)
                ->setBody($responseText);
        }


        // ==========================================
        // Success
        // ==========================================

        $statusNames = [
            1 => 'Hadir',
            2 => 'Izin',
            3 => 'Sakit'
        ];

        $statusName = $statusNames[$status]
            ?? 'Unknown';


        $responseText =
            'Absensi berhasil.' .
            "\n\n" .
            'Nama: ' . $user['name'] .
            "\n" .
            'Status: ' . $statusName .
            "\n" .
            'Jarak: ' . round($distance) . ' meter' .
            "\n" .
            'Tanggal: ' . date('d-m-Y') .
            "\n" .
            'Waktu: ' . date('H:i');


        // ==========================================
        // Save Log
        // ==========================================

        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'location',
            'latitude'     => $latitude,
            'longitude'    => $longitude,
            'status'       => $status,
            'message'      => '[location]',
            'response'     => $responseText
        ]);


        // ==========================================
        // Return
        // ==========================================

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // =====================================================
    // UNSUPPORTED MESSAGE TYPE
    // =====================================================

    $responseText =
        'Message type tidak didukung: ' . $messageType;


    $db->table('absen_wa_test')->insert([
        'user_id'      => $user['id'],
        'phone'        => $phone,
        'message_type' => $messageType,
        'latitude'     => null,
        'longitude'    => null,
        'status'       => null,
        'message'      => '[unsupported]',
        'response'     => $responseText
    ]);


    return $this->response
        ->setStatusCode(200)
        ->setBody($responseText);
}

    public function whatsappWebhook222()
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
    // Get WhatsApp message
    // ==========================================

    $messageData =
        $data['entry'][0]['changes'][0]['value']['messages'][0]
        ?? null;

    // This can happen for status updates, etc.
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


    // ==========================================
    // Get sender and message
    // ==========================================

    $phone = $messageData['from'] ?? null;

    $message = trim(
        $messageData['text']['body'] ?? ''
    );


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
    // Normalize phone number
    // ==========================================

    // Remove spaces, +, -, etc.
    $phone = preg_replace('/[^0-9]/', '', $phone);

    // Meta:
    // 628123456789
    //
    // Database:
    // 08123456789
    //
    // Convert 62xxxxxxxx to 0xxxxxxxx

    $phoneSearch = $phone;

    if (str_starts_with($phone, '62')) {
        $phoneSearch = '0' . substr($phone, 2);
    }


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
        ->where('phone', $phoneSearch)
        ->where('deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();


    // ==========================================
    // User not found
    // ==========================================

    if (!$user) {

        $responseText =
            'User not registered | searched phone: ' . $phoneSearch;

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

    $existing = $db->table('Presensidata')
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
        'created_at'           => date('Y-m-d H:i:s')
    ]);


    // ==========================================
    // Check insert result
    // ==========================================

    if ($db->affectedRows() <= 0) {

        $responseText =
            'Failed to record attendance';

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
    // Success
    // ==========================================

    $responseText =
        'Attendance recorded successfully for ' .
        $user['name'] .
        ' on ' .
        date('d-m-Y');


    // ==========================================
    // Save webhook activity/log
    // ==========================================

    $db->table('absen_wa_test')->insert([
        'user_id'  => $user['id'],
        'phone'    => $phone,
        'message'  => $message,
        'response' => $responseText
    ]);


    // ==========================================
    // Return response to Meta
    // ==========================================

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
