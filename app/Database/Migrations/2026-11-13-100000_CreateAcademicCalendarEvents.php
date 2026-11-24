<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class CreateAcademicCalendarEvents extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT', 'unsigned'=>true, 'auto_increment'=>true],
            'academic_year_id' => ['type'=>'INT', 'unsigned'=>true],
            'title' => ['type'=>'VARCHAR', 'constraint'=>150],
            'description' => ['type'=>'TEXT', 'null'=>true],
            'start_date' => ['type'=>'DATE'],
            'end_date' => ['type'=>'DATE'],
            'category' => ['type'=>'VARCHAR', 'constraint'=>30],
            'created_at' => ['type'=>'DATETIME', 'null'=>true],
            'updated_at' => ['type'=>'DATETIME', 'null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['academic_year_id', 'start_date']);
        $this->forge->createTable('academic_calendar_events', true);
    }
    public function down() { $this->forge->dropTable('academic_calendar_events', true); }
}
