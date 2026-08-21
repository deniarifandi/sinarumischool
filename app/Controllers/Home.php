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
        $this->presence         = new PresenceModel();
        $this->userDivision     = new UserDivisionModel();
        $this->userModel        = new UserModel();
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

        $verifyToken = env(
            'whatsapp.verifyToken',
            'sinarumi_whatsapp_webhook_8f92Kx2026'
        );

        // =====================================================
        // GET - META VERIFICATION
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

        if (!$rawData) {
            log_message('warning', 'WA WEBHOOK: Empty payload');

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // DECODE JSON
        // =====================================================

        $data = json_decode($rawData, true);

        if (!is_array($data)) {

            log_message(
                'error',
                'WA WEBHOOK INVALID JSON: ' . json_last_error_msg()
            );

            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // ONLY PROCESS WHATSAPP BUSINESS ACCOUNT
        // =====================================================

        if (
            ($data['object'] ?? '') !==
            'whatsapp_business_account'
        ) {
            return $this->response
                ->setStatusCode(200)
                ->setBody('OK');
        }

        // =====================================================
        // LOOP ALL ENTRIES
        // =====================================================

        foreach ($data['entry'] ?? [] as $entry) {

            foreach ($entry['changes'] ?? [] as $change) {

                // =================================================
                // ONLY PROCESS "messages"
                // Ignore statuses, account updates, etc.
                // =================================================

                if (($change['field'] ?? '') !== 'messages') {
                    continue;
                }

                $value = $change['value'] ?? [];

                // =================================================
                // NO MESSAGE
                // =================================================

                if (empty($value['messages'])) {
                    continue;
                }

                // =================================================
                // PROCESS EVERY MESSAGE
                // =================================================

                foreach ($value['messages'] as $messageData) {

                    $this->processWhatsAppMessage(
                        $db,
                        $messageData,
                        $rawData
                    );
                }
            }
        }

        // =====================================================
        // ALWAYS RETURN 200 TO META
        // =====================================================

        return $this->response
            ->setStatusCode(200)
            ->setBody('OK');
    }

    // =========================================================
    // PROCESS WHATSAPP MESSAGE
    // =========================================================

    private function processWhatsAppMessage(
        $db,
        array $messageData,
        string $rawData
    ) {
        $now = date('Y-m-d H:i:s');

        // =====================================================
        // MESSAGE ID
        // =====================================================

        $messageId = $messageData['id'] ?? null;

        /*
         * Meta may retry the same webhook.
         * Prevent duplicate processing.
         */

        if ($messageId) {

            $alreadyProcessed = $db->table('absen_wa_test')
                ->where('message_id', $messageId)
                ->countAllResults();

            if ($alreadyProcessed > 0) {

                log_message(
                    'info',
                    'WA DUPLICATE IGNORED: ' . $messageId
                );

                return;
            }
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

            log_message(
                'warning',
                'WA MESSAGE WITHOUT PHONE'
            );

            return;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        // =====================================================
        // CONVERT 62XXXXXXXX -> 0XXXXXXXX
        // =====================================================

        $phoneSearch = $phone;

        if (str_starts_with($phone, '62')) {
            $phoneSearch = '0' . substr($phone, 2);
        }

        // =====================================================
        // MESSAGE TEXT
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
        // ONLY ACCEPT TEXT COMMANDS
        // =====================================================

        if ($messageType !== 'text') {

            log_message(
                'info',
                'WA NON TEXT MESSAGE: ' . $messageType
            );

            return;
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

            $this->saveWhatsAppLog(
                $db,
                [
                    'user_id'      => null,
                    'phone'        => $phone,
                    'message_id'   => $messageId,
                    'message_type' => $messageType,
                    'status'       => null,
                    'message'      => $message,
                    'response'     => $responseText,
                    'raw_payload'  => $rawData,
                    'created_at'   => $now
                ]
            );

            log_message(
                'info',
                'WA USER NOT FOUND: ' .
                $phoneSearch
            );

            log_message(
                'info',
                'WA SEND RESPONSE: ' .
                json_encode($waResponse)
            );

            return;
        }

        // =====================================================
        // COMMAND MAP
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

            $this->saveWhatsAppLog(
                $db,
                [
                    'user_id'      => $user['id'],
                    'phone'        => $phone,
                    'message_id'   => $messageId,
                    'message_type' => $messageType,
                    'status'       => null,
                    'message'      => $message,
                    'response'     => $responseText,
                    'raw_payload'  => $rawData,
                    'created_at'   => $now
                ]
            );

            log_message(
                'info',
                'WA INVALID COMMAND: ' .
                $message
            );

            return;
        }

        // =====================================================
        // ATTENDANCE
        // =====================================================

        $status     = $statusMap[$message];
        $statusName = $statusNames[$status];
        $today      = date('Y-m-d');

        // =====================================================
        // CHECK EXISTING ATTENDANCE
        // =====================================================

        $existing = $db->table('presensidata')
            ->where('guru_id', $user['id'])
            ->where(
                'presensidata_tanggal',
                $today
            )
            ->where(
                'deleted_at IS NULL',
                null,
                false
            )
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

            $waResponse = $this->sendWhatsAppMessage(
                $phone,
                $responseText
            );

            $this->saveWhatsAppLog(
                $db,
                [
                    'user_id'      => $user['id'],
                    'phone'        => $phone,
                    'message_id'   => $messageId,
                    'message_type' => $messageType,
                    'status'       => $status,
                    'message'      => $message,
                    'response'     => $responseText,
                    'raw_payload'  => $rawData,
                    'created_at'   => $now
                ]
            );

            return;
        }

        // =====================================================
        // INSERT PRESENCE
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

            $this->saveWhatsAppLog(
                $db,
                [
                    'user_id'      => $user['id'],
                    'phone'        => $phone,
                    'message_id'   => $messageId,
                    'message_type' => $messageType,
                    'status'       => $status,
                    'message'      => $message,
                    'response'     =>
                        $responseText .
                        "\n\nDB Error: " .
                        json_encode($dbError),
                    'raw_payload'  => $rawData,
                    'created_at'   => $now
                ]
            );

            log_message(
                'error',
                'PRESENCE INSERT FAILED: ' .
                json_encode($dbError)
            );

            return;
        }

        // =====================================================
        // SUCCESS
        // =====================================================

        $responseText =
            "Absensi berhasil.\n\n" .
            "Nama: " . $user['name'] . "\n" .
            "Status: " . $statusName . "\n" .
            "Tanggal: " . date('d-m-Y') . "\n" .
            "Waktu: " . date('H:i');

        $waResponse = $this->sendWhatsAppMessage(
            $phone,
            $responseText
        );

        // =====================================================
        // SAVE LOG
        // =====================================================

        $this->saveWhatsAppLog(
            $db,
            [
                'user_id'      => $user['id'],
                'phone'        => $phone,
                'message_id'   => $messageId,
                'message_type' => $messageType,
                'status'       => $status,
                'message'      => $message,
                'response'     => $responseText,
                'raw_payload'  => $rawData,
                'created_at'   => $now
            ]
        );

        log_message(
            'info',
            'WA ABSENCE SUCCESS: ' .
            json_encode([
                'user_id' => $user['id'],
                'phone'   => $phone,
                'status'  => $status,
                'message' => $message
            ])
        );

        log_message(
            'info',
            'WA SEND RESPONSE: ' .
            json_encode($waResponse)
        );
    }

    // =========================================================
    // SAVE WHATSAPP LOG
    // =========================================================

    private function saveWhatsAppLog($db, array $data)
    {
        try {

            $db->table('absen_wa_test')
                ->insert($data);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'WA LOG ERROR: ' .
                $e->getMessage()
            );
        }
    }

    // =========================================================
    // SEND WHATSAPP MESSAGE
    // =========================================================

    private function sendWhatsAppMessage(
        string $to,
        string $message
    ) {
        $phoneNumberId = "718496908011318";

        $accessToken = "EAAcbbjv93u0BSfWvZAZCCjlwS7yZB6lYB8ADFrI5Qzd9GF7LUSIfxxnKabewxTvQCMkTe2uOQcNZBGTWdVablukGqrr3ABLEbbfuDbkRopXz4bf0fiZBatia2o8EWodu2cOUZBFWPA0T2azIqHH6kuPamdd5TtidrZA8NoctDSnZCwoLuIUbZBLbdMtqwcxvDcMtqO0cZC7h9R29ZAvmFVpPev46c8Hnj2bSPHUEAByhoN9gyz6ZBTdUHuYTBlVzlg6AEAVhGd2UPABrbkdH5w9ySxZAYgrhtsEACzYxZAZCInwTwZDZD";

        $apiVersion = 'v26.0';

        if (!$phoneNumberId || !$accessToken) {

            log_message(
                'error',
                'WhatsApp API credentials are not configured.'
            );

            return [
                'status' => 500,
                'body' => [
                    'error' =>
                        'WhatsApp API credentials not configured'
                ]
            ];
        }

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
                    false,

                'timeout' =>
                    10
            ]);

            $body = $response->getBody();

            return [
                'status' =>
                    $response->getStatusCode(),

                'body' =>
                    json_decode($body, true)
            ];

        } catch (\Throwable $e) {

            log_message(
                'error',
                'WA SEND ERROR: ' .
                $e->getMessage()
            );

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