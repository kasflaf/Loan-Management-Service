<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        // Creating the 'users' table
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
            'email'         => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'role'          => ['type' => 'VARCHAR', 'constraint' => '255', 'default' => 'Member'],
            'created_at'    => ['type' => 'TIMESTAMP', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'updated_at'    => ['type' => 'TIMESTAMP', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
        ]);
        
        // Setting primary key
        $this->forge->addKey('id', true);
        
        // Create the table first
        $this->forge->createTable('users');
        
        // Then add the check constraint
        $this->db->query("ALTER TABLE users ADD CONSTRAINT check_role CHECK (role IN ('Admin', 'Member'))");
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('users');
    }
}