<?php
namespace App\Models;
use CodeIgniter\Model;
class AcademicCalendarEventModel extends Model
{
    protected $table = 'academic_calendar_events';
    protected $allowedFields = ['academic_year_id','title','description','start_date','end_date','category','color'];
    protected $useTimestamps = true;
    public const CATEGORIES = ['activity'=>'Kegiatan', 'exam'=>'Ujian', 'holiday'=>'Libur', 'administration'=>'Administrasi'];
}
