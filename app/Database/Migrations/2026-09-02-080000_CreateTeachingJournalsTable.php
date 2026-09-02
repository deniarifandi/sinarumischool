<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeachingJournalsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'teacher_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'subject_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'class_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'periods' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Jumlah jam pelajaran (JP) yang diajar di jurnal ini',
            ],
            'start_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'end_time' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'topic' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'activities' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('teacher_id');
        $this->forge->addKey('subject_id');
        $this->forge->addKey('class_id');
        $this->forge->addKey('date');

        $this->forge->createTable('teaching_journals');
    }

    public function down()
    {
        $this->forge->dropTable('teaching_journals');
    }
}
