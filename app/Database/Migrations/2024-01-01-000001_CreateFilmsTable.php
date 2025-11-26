<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFilmsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'genre' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'year' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'video_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'poster_path' => [
                'type' => 'VARCHAR',
                'constraint' => 225,
                'null' => true,
            ],
            'rating' => [
                'type' => 'DECIMAL',
                'constraint' => '2,1',
                'default' => 0.0,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('films');
    }

    public function down()
    {
        $this->forge->dropTable('films');
    }
}
