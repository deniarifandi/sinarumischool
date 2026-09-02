<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUnitSubunitToTeachingJournals extends Migration
{
    public function up()
    {
        $this->forge->addColumn('teaching_journals', [
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'class_id',
            ],
            'subunit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'unit_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('teaching_journals', ['unit_id', 'subunit_id']);
    }
}
