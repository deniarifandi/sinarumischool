<?php

namespace App\Controllers;

use App\Models\PresenceModel;
use App\Models\UserDivisionModel;
use App\Models\UserModel;
use App\Models\UserSubjectModel;

class Home extends BaseController
{
    protected $presence;
    protected $userDivision;
    protected $userModel;
    protected $UserSubjectModel;

    public function __construct()
    {
        $this->presence          = new PresenceModel();
        $this->userDivision      = new UserDivisionModel();
        $this->userModel         = new UserModel();
        $this->UserSubjectModel  = new UserSubjectModel();
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

        $allowedRoles = [
            'superadmin',
            'teacher',
            'teacher_admin'
        ];

        $groupedSubjects = [];

        if (
            in_array($userDetail['role'], $allowedRoles)
            && !empty($userSubjects)
        ) {
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

    // =========================================================
    // WHATSAPP WEBHOOK
    // =========================================================

    public function whatsappWebhook()
    {
        $db = \Config\Database::connect();

        $verifyToken = 'sinarumi_whatsapp_webhook_8f92Kx2026';

        // =====================================================
        // GET - META WEBHOOK VERIFICATION
        // =====================================================

        if ($this->request->getMethod() === 'GET') {

            $mode      = $this->request->getGet('hub_mode');
            $token     = $this->request->getGet('hub_verify_token');
            $challenge = $this->request->getGet('hub_challenge');

            if (
                $mode === 'subscribe'
                && $token === $verifyToken
            ) {
                return $this->response
                    ->setStatusCode(200)
                    ->setBody($challenge);
            }

            return $this->response
                ->setStatusCode(403)
                ->setBody('Verification failed');
        }

        // =====================================================
        // POST - RECEIVE WEBHOOK
        // =====================================================

        $rawData = file_get_contents('php://input');

        // =====================================================
        // SAVE RAW WEBHOOK FIRST
        // =====================================================

        try {

            $db->table('absen_wa_test')->insert([
                'user_id'      => null,
                'phone'        => null,
                'message_type' => 'webhook',
                'status'       => null,
                'message'      => 'WEBHOOK_RECEIVED',
                'response'     => null,
                'raw_payload'  => $rawData,
                'created_at'   => date('Y-m-d H:i:s')
            ]);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'WA LOG ERROR: ' . $e->getMessage()
            );
        }

        // =====================================================
        // DECODE JSON
        // =====================================================

        $data = json_decode($rawData, true);

        if (!is_array($data)) {

            try {
                $db->table('absen_wa_test')->insert([
                    'user_id'      => null,
                    'phone'        => null,
                    'message_type' => 'error',
                    'status'       => null,
                    'message'      => 'INVALID_JSON',
                    'response'     => json_last_error_msg(),
                    'raw_payload'  => $rawData,
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            } catch (\Throwable $e) {
                log_message('error', $e->getMessage());
            }

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // GET MESSAGE
        // =====================================================

        $messageData =
            $data['entry'][0]['changes'][0]['value']['messages'][0]
            ?? null;

        if (!$messageData) {

            try {
                $db->table('absen_wa_test')->insert([
                    'user_id'      => null,
                    'phone'        => null,
                    'message_type' => 'webhook',
                    'status'       => null,
                    'message'      => 'NO_MESSAGE',
                    'response'     => null,
                    'raw_payload'  => $rawData,
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            } catch (\Throwable $e) {
                log_message('error', $e->getMessage());
            }

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // MESSAGE TYPE
        // =====================================================

        $messageType = $messageData['type'] ?? null;

        // =====================================================
        // SENDER PHONE
        // =====================================================

        $phone = $messageData['from'] ?? null;

        if (!$phone) {

            try {
                $db->table('absen_wa_test')->insert([
                    'user_id'      => null,
                    'phone'        => null,
                    'message_type' => $messageType,
                    'status'       => null,
                    'message'      => 'NO_PHONE',
                    'response'     => null,
                    'raw_payload'  => $rawData,
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            } catch (\Throwable $e) {
                log_message('error', $e->getMessage());
            }

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        // =====================================================
        // CONVERT 62XXXXXXXXX -> 0XXXXXXXXX
        // =====================================================

        $phoneSearch = $phone;

        if (str_starts_with($phone, '62')) {
            $phoneSearch = '0' . substr($phone, 2);
        }

        // =====================================================
        // MESSAGE
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
        // USER NOT FOUND
        // =====================================================

        if (!$user) {

            $responseText =
                "Nomor WhatsApp Anda belum terdaftar.\n\n" .
                "Nomor: " . $phoneSearch;

            $waResponse = $this->sendWhatsAppMessage(
                $phone,
                $responseText
            );

            try {

                $db->table('absen_wa_test')->insert([
                    'user_id'      => null,
                    'phone'        => $phone,
                    'message_type' => $messageType,
                    'status'       => null,
                    'message'      => $message,
                    'response'     => $responseText,
                    'raw_payload'  => $rawData,
                    'created_at'   => date('Y-m-d H:i:s')
                ]);

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'WA LOG ERROR: ' . $e->getMessage()
                );
            }

            log_message(
                'info',
                'WA SEND RESPONSE: ' . json_encode($waResponse)
            );

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // COMMAND
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

            $waResponse = $this->sendWhatsAppMessage(
                $phone,
                $responseText
            );

            try {

                $db->table('absen_wa_test')->insert([
                    'user_id'      => $user['id'],
                    'phone'        => $phone,
                    'message_type' => $messageType,
                    'status'       => null,
                    'message'      => $message,
                    'response'     => $responseText,
                    'raw_payload'  => $rawData,
                    'created_at'   => date('Y-m-d H:i:s')
                ]);

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'WA LOG ERROR: ' . $e->getMessage()
                );
            }

            log_message(
                'info',
                'WA SEND RESPONSE: ' . json_encode($waResponse)
            );

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // ATTENDANCE DATA
        // =====================================================

        $status     = $statusMap[$message];
        $statusName = $statusNames[$status];
        $today      = date('Y-m-d');
        $now        = date('Y-m-d H:i:s');

        // =====================================================
        // CHECK EXISTING ATTENDANCE
        // =====================================================

        $existing = $db->table('presensidata')
            ->where('guru_id', $user['id'])
            ->where('presensidata_tanggal', $today)
            ->where('deleted_at IS NULL', null, false)
            ->get()
            ->getRowArray();

        if ($existing) {

            $existingStatus = (int) $existing['status'];

            $existingStatusName =
                $statusNames[$existingStatus] ?? 'Unknown';

            $responseText =
                "Absensi hari ini sudah tercatat.\n\n" .
                "Nama: " . $user['name'] . "\n" .
                "Status: " . $existingStatusName;

            $waResponse = $this->sendWhatsAppMessage(
                $phone,
                $responseText
            );

            try {

                $db->table('absen_wa_test')->insert([
                    'user_id'      => $user['id'],
                    'phone'        => $phone,
                    'message_type' => $messageType,
                    'status'       => $status,
                    'message'      => $message,
                    'response'     => $responseText,
                    'raw_payload'  => $rawData,
                    'created_at'   => $now
                ]);

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'WA LOG ERROR: ' . $e->getMessage()
                );
            }

            log_message(
                'info',
                'WA SEND RESPONSE: ' . json_encode($waResponse)
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
            'created_at'           => $now
        ];

        $inserted = $db->table('presensidata')
            ->insert($insertData);

        // =====================================================
        // INSERT FAILED
        // =====================================================

        if (!$inserted) {

            $dbError = $db->error();

            $responseText =
                "Absensi gagal disimpan.\n\n" .
                "Nama: " . $user['name'];

            $waResponse = $this->sendWhatsAppMessage(
                $phone,
                $responseText
            );

            try {

                $db->table('absen_wa_test')->insert([
                    'user_id'      => $user['id'],
                    'phone'        => $phone,
                    'message_type' => $messageType,
                    'status'       => $status,
                    'message'      => $message,
                    'response'     =>
                        $responseText .
                        "\n\nDB Error: " .
                        json_encode($dbError),
                    'raw_payload'  => $rawData,
                    'created_at'   => $now
                ]);

            } catch (\Throwable $e) {

                log_message(
                    'error',
                    'WA LOG ERROR: ' . $e->getMessage()
                );
            }

            log_message(
                'error',
                'PRESENCE INSERT FAILED: ' .
                json_encode($dbError)
            );

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // SUCCESS RESPONSE
        // =====================================================

        $responseText =
            "Absensi berhasil.\n\n" .
            "Nama: " . $user['name'] . "\n" .
            "Status: " . $statusName . "\n" .
            "Tanggal: " . date('d-m-Y') . "\n" .
            "Waktu: " . date('H:i');

        // =====================================================
        // SEND WHATSAPP RESPONSE
        // =====================================================

        $waResponse = $this->sendWhatsAppMessage(
            $phone,
            $responseText
        );

        // =====================================================
        // SAVE LOG
        // =====================================================

        try {

            $db->table('absen_wa_test')->insert([
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_type' => $messageType,
                'status'       => $status,
                'message'      => $message,
                'response'     => $responseText,
                'raw_payload'  => $rawData,
                'created_at'   => $now
            ]);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'WA LOG ERROR: ' . $e->getMessage()
            );
        }

        log_message(
            'info',
            'WA SEND RESPONSE: ' . json_encode($waResponse)
        );

        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }

    // =========================================================
    // SEND WHATSAPP MESSAGE
    // =========================================================

    private function sendWhatsAppMessage(
        string $to,
        string $message
    ) {
        /*
         * Put your NEW access token here,
         * or preferably move these values to .env
         */

        $phoneNumberId = '718496908011318';
        $accessToken   = 'EAAcbbjv93u0BSeOZA66DE42hxkzY5qytL2KK8ZBkHsVkc1dvFcyzXg89UkS9VPj0bJHpqNtVZB0KzNX7QYmRrcCxl20bcXya0xL2sP8P3XSsU1DUHgI8DV9hRBhEupvmfEDzDBZAHqZCmQTVqWBaufOg8cuO39ETZBBxpZCDNe4ia0lWZAxiehQQCsivFZC5ArtUdwwwRysWxxa7o7lrd77k9yFzr6OJQ54jfglprP5wCzIKssG5KhDlHNFaS4zXQFj3fTQsVSA0rhj00ZAn58MaYtBmonRSz5gOMlzICx4ZCsZD';
        $apiVersion    = 'v26.0';

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
                    'Authorization' =>
                        'Bearer ' . $accessToken,

                    'Content-Type' =>
                        'application/json'
                ],

                'body' =>
                    json_encode($payload),

                'http_errors' =>
                    false
            ]);

            $body = $response->getBody();

            return [
                'status' =>
                    $response->getStatusCode(),

                'body' =>
                    json_decode($body, true)
            ];

        } catch (\Throwable $e) {

            return [
                'status' => 500,

                'body' => [
                    'error' =>
                        $e->getMessage()
                ]
            ];
        }
    }
}