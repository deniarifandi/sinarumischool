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

    if (!$messageData) {
        return $this->response
            ->setStatusCode(200)
            ->setBody('No message');
    }


    // ==========================================
    // Sender Phone
    // ==========================================

    $phone = $messageData['from'] ?? null;

    if (!$phone) {
        return $this->response
            ->setStatusCode(200)
            ->setBody('No sender');
    }

    // Keep numbers only
    $phone = preg_replace('/[^0-9]/', '', $phone);


    // ==========================================
    // Normalize Indonesia Phone Number
    // WhatsApp: 628123456789
    // Database: 08123456789
    // ==========================================

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
    // User Not Registered
    // ==========================================

    if (!$user) {

        $responseText =
            'Nomor WhatsApp Anda belum terdaftar.' .
            "\n\n" .
            'Nomor: ' . $phoneSearch;

        $db->table('absen_wa_test')->insert([
            'user_id'      => null,
            'phone'        => $phone,
            'message_type' => $messageData['type'] ?? null,
            'status'       => null,
            'message'      => $messageData['text']['body'] ?? '',
            'response'     => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Only Process Text Messages
    // ==========================================

    if (($messageData['type'] ?? '') !== 'text') {

        $responseText =
            'Silakan kirim salah satu perintah:' .
            "\n\n" .
            'absen' .
            "\n" .
            'izin' .
            "\n" .
            'sakit';

        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => $messageData['type'] ?? null,
            'status'       => null,
            'message'      => '[non-text]',
            'response'     => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Get Message
    // ==========================================

    $message = strtolower(trim(
        $messageData['text']['body'] ?? ''
    ));


    // ==========================================
    // Attendance Status
    // ==========================================

    $statusMap = [
        'absen' => 1,
        'izin'  => 2,
        'sakit' => 3,
    ];


    // ==========================================
    // Invalid Command
    // ==========================================

    if (!isset($statusMap[$message])) {

        $responseText =
            'Halo ' . $user['name'] . '.' .
            "\n\n" .
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
            'status'       => null,
            'message'      => $message,
            'response'     => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Get Status
    // ==========================================

    $status = $statusMap[$message];

    $statusNames = [
        1 => 'Hadir',
        2 => 'Izin',
        3 => 'Sakit'
    ];

    $statusName = $statusNames[$status];


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
    // Already Recorded
    // ==========================================

    if ($existing) {

        $existingStatus = (int) $existing['status'];

        $existingStatusName =
            $statusNames[$existingStatus]
            ?? 'Unknown';

        $responseText =
            'Absensi hari ini sudah tercatat.' .
            "\n\n" .
            'Nama: ' . $user['name'] .
            "\n" .
            'Status: ' . $existingStatusName;


        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'text',
            'status'       => $status,
            'message'      => $message,
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
        'longitude'            => null,
        'latitude'             => null,
        'address'              => null,
        'presensidata_tanggal' => $today,
        'status'               => $status,
        'created_at'            => date('Y-m-d H:i:s')
    ];

    $inserted = $db->table('presensidata')
        ->insert($insertData);


    // ==========================================
    // Failed
    // ==========================================

    if (!$inserted) {

        $responseText =
            'Absensi gagal disimpan.' .
            "\n\n" .
            'Nama: ' . $user['name'] .
            "\n" .
            'Status: ' . $statusName;


        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'text',
            'status'       => $status,
            'message'      => $message,
            'response'     => $responseText
        ]);

        return $this->response
            ->setStatusCode(200)
            ->setBody($responseText);
    }


    // ==========================================
    // Success Response
    // ==========================================

    $responseText =
        'Absensi berhasil.' .
        "\n\n" .
        'Nama: ' . $user['name'] .
        "\n" .
        'Status: ' . $statusName .
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
        'message_type' => 'text',
        'status'       => $status,
        'message'      => $message,
        'response'     => $responseText
    ]);


    // ==========================================
    // Return
    // ==========================================

    return $this->response
        ->setStatusCode(200)
        ->setBody($responseText);
}

public function whatsappWebhook()
{
    $db = \Config\Database::connect();

    // =====================================================
    // CONFIGURATION
    // =====================================================

    $verifyToken  = 'sinarumi_whatsapp_webhook_8f92Kx2026';
    $phoneNumberId = '718496908011318';
    $accessToken   = 'EAAcbbjv93u0BSeOZA66DE42hxkzY5qytL2KK8ZBkHsVkc1dvFcyzXg89UkS9VPj0bJHpqNtVZB0KzNX7QYmRrcCxl20bcXya0xL2sP8P3XSsU1DUHgI8DV9hRBhEupvmfEDzDBZAHqZCmQTVqWBaufOg8cuO39ETZBBxpZCDNe4ia0lWZAxiehQQCsivFZC5ArtUdwwwRysWxxa7o7lrd77k9yFzr6OJQ54jfglprP5wCzIKssG5KhDlHNFaS4zXQFj3fTQsVSA0rhj00ZAn58MaYtBmonRSz5gOMlzICx4ZCsZD';
    $apiVersion    = 'v26.0';


    // =====================================================
    // GET - META WEBHOOK VERIFICATION
    // =====================================================

    if ($this->request->getMethod() === 'GET') {

        $mode = $this->request->getGet('hub_mode');
        $token = $this->request->getGet('hub_verify_token');
        $challenge = $this->request->getGet('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $this->response
                ->setStatusCode(200)
                ->setBody($challenge);
        }

        return $this->response
            ->setStatusCode(403)
            ->setBody('Verification failed');
    }


    // =====================================================
    // POST - RECEIVE WHATSAPP WEBHOOK
    // =====================================================

    $rawData = file_get_contents('php://input');

    $data = json_decode($rawData, true);

    if (!$data) {

        return $this->response
            ->setStatusCode(400)
            ->setBody('Invalid JSON');
    }


    // =====================================================
    // GET MESSAGE
    // =====================================================

    $messageData =
        $data['entry'][0]['changes'][0]['value']['messages'][0]
        ?? null;


    /*
     * Meta can send other webhook events such as
     * message status updates.
     */

    if (!$messageData) {

        return $this->response
            ->setStatusCode(200)
            ->setBody('No message');
    }


    // =====================================================
    // SENDER PHONE
    // =====================================================

    $phone = $messageData['from'] ?? null;

    if (!$phone) {

        return $this->response
            ->setStatusCode(200)
            ->setBody('No sender');
    }


    // Remove +, spaces, -, etc.
    $phone = preg_replace('/[^0-9]/', '', $phone);


    // =====================================================
    // NORMALIZE PHONE NUMBER
    //
    // WhatsApp:
    // 628123456789
    //
    // Database:
    // 08123456789
    // =====================================================

    $phoneSearch = $phone;

    if (str_starts_with($phone, '62')) {

        $phoneSearch = '0' . substr($phone, 2);
    }


    // =====================================================
    // MESSAGE TYPE
    // =====================================================

    $messageType = $messageData['type'] ?? null;


    // =====================================================
    // GET MESSAGE TEXT
    // =====================================================

    $message = '';

    if ($messageType === 'text') {

        $message = strtolower(
            trim(
                $messageData['text']['body'] ?? ''
            )
        );
    }


    // =====================================================
    // FIND USER
    // =====================================================

    $user = $db->table('users')
        ->select('id, name, phone')
        ->where('phone', $phoneSearch)
        ->where('deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();


    // =====================================================
    // USER NOT REGISTERED
    // =====================================================

    if (!$user) {

        $responseText =
            "Nomor WhatsApp Anda belum terdaftar.\n\n" .
            "Nomor: " . $phoneSearch;

        // Log
        $db->table('absen_wa_test')->insert([
            'user_id'      => null,
            'phone'        => $phone,
            'message_type' => $messageType,
            'status'       => null,
            'message'      => $message,
            'response'     => $responseText
        ]);

        // Send WhatsApp response
        $this->sendWhatsAppMessage(
            $phone,
            $responseText,
            $phoneNumberId,
            $accessToken,
            $apiVersion
        );

        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }


    // =====================================================
    // ONLY ACCEPT TEXT MESSAGE
    // =====================================================

    if ($messageType !== 'text') {

        $responseText =
            "Silakan gunakan perintah berikut:\n\n" .
            "absen\n" .
            "izin\n" .
            "sakit";

        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => $messageType,
            'status'       => null,
            'message'      => '[non-text]',
            'response'     => $responseText
        ]);

        $this->sendWhatsAppMessage(
            $phone,
            $responseText,
            $phoneNumberId,
            $accessToken,
            $apiVersion
        );

        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }


    // =====================================================
    // ATTENDANCE STATUS
    // =====================================================

    $statusMap = [
        'absen' => 1,
        'izin'  => 2,
        'sakit' => 3
    ];

    $statusNames = [
        1 => 'Hadir',
        2 => 'Izin',
        3 => 'Sakit'
    ];


    // =====================================================
    // INVALID COMMAND
    // =====================================================

    if (!isset($statusMap[$message])) {

        $responseText =
            "Halo " . $user['name'] . ".\n\n" .
            "Perintah tidak dikenali.\n\n" .
            "Gunakan:\n" .
            "absen = Hadir\n" .
            "izin = Izin\n" .
            "sakit = Sakit";

        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'text',
            'status'       => null,
            'message'      => $message,
            'response'     => $responseText
        ]);

        $this->sendWhatsAppMessage(
            $phone,
            $responseText,
            $phoneNumberId,
            $accessToken,
            $apiVersion
        );

        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }


    // =====================================================
    // GET STATUS
    // =====================================================

    $status = $statusMap[$message];

    $statusName = $statusNames[$status];


    // =====================================================
    // TODAY
    // =====================================================

    $today = date('Y-m-d');


    // =====================================================
    // CHECK EXISTING ATTENDANCE
    // =====================================================

    $existing = $db->table('presensidata')
        ->where('guru_id', $user['id'])
        ->where('presensidata_tanggal', $today)
        ->where('deleted_at IS NULL', null, false)
        ->get()
        ->getRowArray();


    // =====================================================
    // ALREADY ATTENDED
    // =====================================================

    if ($existing) {

        $existingStatus = (int) $existing['status'];

        $existingStatusName =
            $statusNames[$existingStatus]
            ?? 'Unknown';

        $responseText =
            "Absensi hari ini sudah tercatat.\n\n" .
            "Nama: " . $user['name'] . "\n" .
            "Status: " . $existingStatusName;


        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'text',
            'status'       => $status,
            'message'      => $message,
            'response'     => $responseText
        ]);


        $this->sendWhatsAppMessage(
            $phone,
            $responseText,
            $phoneNumberId,
            $accessToken,
            $apiVersion
        );


        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }


    // =====================================================
    // INSERT ATTENDANCE
    // =====================================================

    $insertData = [
        'guru_id'              => $user['id'],
        'longitude'            => null,
        'latitude'             => null,
        'address'              => null,
        'presensidata_tanggal' => $today,
        'status'               => $status,
        'created_at'           => date('Y-m-d H:i:s')
    ];


    $inserted = $db->table('presensidata')
        ->insert($insertData);


    // =====================================================
    // INSERT FAILED
    // =====================================================

    if (!$inserted) {

        $responseText =
            "Absensi gagal disimpan.\n\n" .
            "Nama: " . $user['name'] . "\n" .
            "Status: " . $statusName;


        $db->table('absen_wa_test')->insert([
            'user_id'      => $user['id'],
            'phone'        => $phone,
            'message_type' => 'text',
            'status'       => $status,
            'message'      => $message,
            'response'     => $responseText
        ]);


        $this->sendWhatsAppMessage(
            $phone,
            $responseText,
            $phoneNumberId,
            $accessToken,
            $apiVersion
        );


        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }


    // =====================================================
    // SUCCESS MESSAGE
    // =====================================================

    $responseText =
        "Absensi berhasil.\n\n" .
        "Nama: " . $user['name'] . "\n" .
        "Status: " . $statusName . "\n" .
        "Tanggal: " . date('d-m-Y') . "\n" .
        "Waktu: " . date('H:i');


    // =====================================================
    // SAVE LOG
    // =====================================================

    $db->table('absen_wa_test')->insert([
        'user_id'      => $user['id'],
        'phone'        => $phone,
        'message_type' => 'text',
        'status'       => $status,
        'message'      => $message,
        'response'     => $responseText
    ]);


    // =====================================================
    // SEND WHATSAPP RESPONSE
    // =====================================================

    $waResponse = $this->sendWhatsAppMessage(
        $phone,
        $responseText,
        $phoneNumberId,
        $accessToken,
        $apiVersion
    );


    // =====================================================
    // RETURN TO META
    // =====================================================

    return $this->response
        ->setStatusCode(200)
        ->setBody('OK');
}


/**
 * Send text message through WhatsApp Cloud API
 */
private function sendWhatsAppMessage(
    string $to,
    string $message,
    string $phoneNumberId,
    string $accessToken,
    string $apiVersion = 'v26.0'
) {
    $url =
        "https://graph.facebook.com/" .
        $apiVersion .
        "/" .
        $phoneNumberId .
        "/messages";


    $payload = [
        'messaging_product' => 'whatsapp',
        'to'                => $to,
        'type'              => 'text',
        'text'              => [
            'body' => $message
        ]
    ];


    $client = \Config\Services::curlrequest();


    try {

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json'
            ],
            'body' => json_encode($payload),
            'http_errors' => false
        ]);


        return [
            'status' => $response->getStatusCode(),
            'body'   => json_decode(
                $response->getBody(),
                true
            )
        ];

    } catch (\Throwable $e) {

        return [
            'status' => 500,
            'body'   => [
                'error' => $e->getMessage()
            ]
        ];
    }
}

private function sendWhatsAppMessage(string $to, string $message)
{
    $phoneNumberId = '718496908011318';
    $accessToken   = 'EAAcbbjv93u0BSeOZA66DE42hxkzY5qytL2KK8ZBkHsVkc1dvFcyzXg89UkS9VPj0bJHpqNtVZB0KzNX7QYmRrcCxl20bcXya0xL2sP8P3XSsU1DUHgI8DV9hRBhEupvmfEDzDBZAHqZCmQTVqWBaufOg8cuO39ETZBBxpZCDNe4ia0lWZAxiehQQCsivFZC5ArtUdwwwRysWxxa7o7lrd77k9yFzr6OJQ54jfglprP5wCzIKssG5KhDlHNFaS4zXQFj3fTQsVSA0rhj00ZAn58MaYtBmonRSz5gOMlzICx4ZCsZD';
    $apiVersion    = 'v26.0';

    $url = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages";

    $payload = [
        'messaging_product' => 'whatsapp',
        'to'                => $to,
        'type'              => 'text',
        'text'              => [
            'body' => $message
        ]
    ];

    $client = \Config\Services::curlrequest();

    try {

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json'
            ],
            'body' => json_encode($payload),
            'http_errors' => false
        ]);

        return [
            'status' => $response->getStatusCode(),
            'body'   => json_decode($response->getBody(), true)
        ];

    } catch (\Throwable $e) {

        return [
            'status' => 500,
            'body'   => [
                'error' => $e->getMessage()
            ]
        ];
    }
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
