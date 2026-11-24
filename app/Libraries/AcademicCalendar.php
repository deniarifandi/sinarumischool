<?php

namespace App\Libraries;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

class AcademicCalendar
{
    public static function eventColor(array $event): string
    {
        if (isset($event['color']) && preg_match('/^#[0-9a-fA-F]{6}$/D', $event['color'])) {
            return $event['color'];
        }
        return ['activity'=>'#0dcaf0','exam'=>'#dc3545','holiday'=>'#ffc107','administration'=>'#212529'][$event['category'] ?? ''] ?? '#0dcaf0';
    }

    public static function textColor(string $color): string
    {
        $rgb = sscanf($color, '#%02x%02x%02x');
        return ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000 >= 150 ? '#000000' : '#ffffff';
    }

    public static function validateEvent(array $data, array $year): array
    {
        $errors = [];
        if (!isset($data['color']) || !preg_match('/^#[0-9a-fA-F]{6}$/D', $data['color'])) $errors[] = 'Warna event tidak valid.';
        if ($data['title'] === '' || mb_strlen($data['title']) > 150) $errors[] = 'Nama event wajib diisi, maksimal 150 karakter.';
        if (mb_strlen($data['description']) > 5000) $errors[] = 'Keterangan maksimal 5000 karakter.';
        if (!array_key_exists($data['category'], \App\Models\AcademicCalendarEventModel::CATEGORIES)) $errors[] = 'Jenis event tidak valid.';
        foreach (['start_date','end_date'] as $field) {
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $data[$field]);
            if (!$date || $date->format('Y-m-d') !== $data[$field]) $errors[] = 'Tanggal event tidak valid.';
        }
        if (!$errors && ($data['end_date'] < $data['start_date'] || $data['start_date'] < $year['start_date'] || $data['end_date'] > $year['end_date'])) {
            $errors[] = 'Rentang tanggal harus berurutan dan berada dalam tahun ajaran terpilih.';
        }
        return $errors;
    }

    public function build(array $year, array $terms, $requestedMonth = null, array $customEvents = []): array
    {
        $zone = new DateTimeZone('Asia/Bangkok');
        $start = new DateTimeImmutable($year['start_date'], $zone);
        $end = new DateTimeImmutable($year['end_date'], $zone);
        if ($end < $start) {
            throw new InvalidArgumentException('Tanggal tahun ajaran tidak valid.');
        }
        $first = $start->modify('first day of this month')->setTime(0, 0);
        $last = $end->modify('first day of this month')->setTime(0, 0);
        $today = new DateTimeImmutable('today', $zone);
        $month = $today->modify('first day of this month');
        if ($requestedMonth !== null && $requestedMonth !== '') {
            if (!is_string($requestedMonth) || !preg_match('/^\d{4}-(0[1-9]|1[0-2])$/D', $requestedMonth)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Bulan tidak valid.');
            }
            $month = new DateTimeImmutable($requestedMonth . '-01', $zone);
        }
        $month = $month < $first ? $first : ($month > $last ? $last : $month);
        $months = [];
        for ($date = $first; $date <= $last; $date = $date->modify('+1 month')) {
            $months[$date->format('Y-m')] = $date->format('F Y');
        }
        $events = [];
        $events[$start->format('Y-m-d')][] = ['label' => 'Academic Year Starts', 'type' => 'primary'];
        $events[$end->format('Y-m-d')][] = ['label' => 'Academic Year Ends', 'type' => 'secondary'];
        foreach ($terms as $term) {
            foreach (['start_date' => ['Starts', 'primary'], 'end_date' => ['Ends', 'secondary'], 'report_date' => ['Report Card', 'success']] as $field => [$label, $type]) {
                $date = $term[$field] ?? '';
                if ($date && $date >= $year['start_date'] && $date <= $year['end_date']) {
                    $events[$date][] = ['label' => $term['semester_name'] . ' / ' . $term['name'] . ' — ' . $label, 'type' => $type];
                }
            }
        }
        foreach ($customEvents as $event) {
            $eventStart = max($event['start_date'], $month->format('Y-m-d'), $year['start_date']);
            $eventEnd = min($event['end_date'], $month->modify('last day of this month')->format('Y-m-d'), $year['end_date']);
            for ($date = new DateTimeImmutable($eventStart, $zone); $date->format('Y-m-d') <= $eventEnd; $date = $date->modify('+1 day')) {
                $events[$date->format('Y-m-d')][] = ['label'=>$event['title'], 'type'=>'info', 'color'=>self::eventColor($event), 'textColor'=>self::textColor(self::eventColor($event))];
            }
        }
        $gridStart = $month->modify('-' . ((int) $month->format('N') - 1) . ' days');
        $days = [];
        $monthEnd = $month->modify('last day of this month');
        $gridEnd = $monthEnd->modify('+' . (7 - (int) $monthEnd->format('N')) . ' days');
        for ($date = $gridStart; $date <= $gridEnd; $date = $date->modify('+1 day')) {
            $key = $date->format('Y-m-d');
            $days[] = [
                'date' => $key, 'number' => $date->format('j'),
                'inMonth' => $date->format('Y-m') === $month->format('Y-m'),
                'inYear' => $key >= $year['start_date'] && $key <= $year['end_date'],
                'today' => $key === $today->format('Y-m-d'),
                'weekend' => (int) $date->format('N') >= 6,
                'events' => $events[$key] ?? [],
            ];
        }
        return [
            'month' => $month->format('Y-m'), 'label' => $month->format('F Y'),
            'months' => $months, 'days' => $days,
            'previous' => $month > $first ? $month->modify('-1 month')->format('Y-m') : null,
            'next' => $month < $last ? $month->modify('+1 month')->format('Y-m') : null,
        ];
    }
}
