<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoanTable extends Migration
{
    public function up()
    {
        // Create the ENUM type for 'status'
        $this->db->query("CREATE TYPE loan_status AS ENUM ('active', 'overdue', 'returned');");

        // Create the 'loans' table
        $this->forge->addField([
            'loan_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
            ],
            'book_id' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255', // Use VARCHAR to handle book IDs from external databases
                'null'           => false,
            ],
            'loan_start_date' => [
                'type'       => 'TIMESTAMP',
                'null'       => false,
            ],
            'loan_due_date' => [
                'type'       => 'TIMESTAMP',
                'null'       => false,
            ],
            'loan_returned_date' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'loan_status',  // Referencing the custom ENUM type
                'default'    => 'active',
            ],
            'penalty' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => '0.00',
            ],
            'created_at'    => ['type' => 'TIMESTAMP', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'updated_at'    => ['type' => 'TIMESTAMP', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
        ]);

        // Add the primary key
        $this->forge->addPrimaryKey('loan_id');

        // Add foreign key for `user_id` referencing `users` table
        // Match the `id` column name in the `users` table
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        // Create the table
        $this->forge->createTable('loans');
    }

    public function down()
    {
        // Drop the 'loans' table
        $this->forge->dropTable('loans');
        
        // Drop the custom ENUM type
        $this->db->query("DROP TYPE IF EXISTS loan_status;");
    }
}
