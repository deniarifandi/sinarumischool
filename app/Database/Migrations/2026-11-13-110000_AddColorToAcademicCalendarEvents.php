<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddColorToAcademicCalendarEvents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('academic_calendar_events', [
            'color' => ['type'=>'VARCHAR', 'constraint'=>7, 'null'=>true],
        ]);
    }
    public function down() { $this->forge->dropColumn('academic_calendar_events', 'color'); }
}
