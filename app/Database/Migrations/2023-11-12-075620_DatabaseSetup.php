<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DatabaseSetup extends Migration
{
    public function up()
    {
                // create settings 
                $this->forge->addField([
                    'id' => [
                        'type' => 'INT',
                        'constraint' => 9,
                        'auto_increment' => true
                    ],
                    'class' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => false,
                    ],
                    'key' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => false,
                    ],
                    'value' => [
                        'type' => 'text',
                        'null' => true,
                    ],
                    'type' => [
                        'type' => 'VARCHAR',
                        'constraint' => 31,
                    ],
                    'context' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => false
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => false
                    ]
                ]);
                $this->forge->addKey('id', true);
                $this->forge->createTable('settings');
                // create user table
                $this->forge->addField([
                    'user_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'auto_increment' => true
                    ],
                    'fullname' => [
                       'type' => 'VARCHAR',
                       'constraint' => 50,
                    ],
                     'username' => [
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                    ],
                    'email' => [
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                    ],
                    'password' => [
                        'type' => 'VARCHAR',
                        'constraint' => 200,
                    ],
                    'role' => [
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ]
                ]);
                $this->forge->addKey('user_id', true);
                $this->forge->createTable('user_account');

                // create office table

                $this->forge->addField([
                    'office_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'auto_increment' => true
                    ],
                    'office_name' => [
                        'type' => 'VARCHAR',
                        'constraint' => 200,
                    ],
                    'office_code' => [
                        'type' => 'VARCHAR',
                        'constraint' => 10,
                    ],
                    'divisionorsection_name' => [
                        'type' => 'VARCHAR',
                        'constraint' => 200,
                    ],
                    'divisionorsection_code' => [
                        'type' => 'varchar',
                        'constraint' => 20,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ]
                ]);
                $this->forge->addKey('office_id', true);
                $this->forge->createTable('office');

                // create categories table

                $this->forge->addField([
                    'category_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'auto_increment' => true
                    ],
                    'severity' => [
                        'type' => 'VARCHAR',
                        'constraint' => 100,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ]
                ]);
                $this->forge->addKey('category_id', true);
                $this->forge->createTable('categories');

                // create ticket table

                $this->forge->addField([
                    'ticket_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'auto_increment' => true
                    ],
                     'first_name' => [
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                    ],
                    'last_name' => [
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                    ],
                    'email' => [
                        'type' => 'VARCHAR',
                        'constraint' => 200,
                    ],
                    'category_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                    ],
                    'office_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                    ],
                    'user_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                    ],
                    'description' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                    ],
                    'status' => [
                        'type' => 'varchar',
                        'constraint' => 50,
                    ],
                    'created_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'updated_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ],
                    'deleted_at' => [
                        'type' => 'DATETIME',
                        'null' => true
                    ]
                ]);
                $this->forge->addKey('ticket_id', true);
                $this->forge->createTable('ticket');

                // create default user account as "ADMINISTRATOR OR ADMIN in user_account table"
                // see AddDefaultUser

               
    }

    public function down()
    {
        //
    }
}
