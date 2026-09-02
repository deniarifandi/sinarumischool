<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTermIdToObjectives extends Migration
{
    public function up()
    {
        $this->forge->addColumn('objectives', [
            'term_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'outcome_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('objectives', ['term_id']);
    }
}
