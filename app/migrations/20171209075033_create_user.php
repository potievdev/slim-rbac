<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateUser
 */
class CreateUser extends AbstractMigration
{
    /**
     * Create user table in database.
     * Fields 'username' and 'email' are unique indexes.
     */
    public function change()
    {
        $userTable = $this->table('user', ['signed' => false]);

        $userTable->addColumn('username', 'string', ['limit' => 100])
            ->addColumn('password', 'string', ['limit' => 100])
            ->addColumn('password_salt', 'string', ['limit' => 100])
            ->addColumn('email', 'string', ['limit' => 150])
            ->addColumn('first_name', 'string', ['limit' => 60])
            ->addColumn('last_name', 'string', ['limit' => 60])
            ->addColumn('status', 'boolean', ['default' => 0])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex('username', ['name' => 'idx_user_username' ,'unique' => true])
            ->addIndex('email', ['name' => 'idx_user_email' ,'unique' => true])
            ->create();
    }
}
