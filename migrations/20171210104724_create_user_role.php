<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateUserRole
 */
class CreateUserRole extends AbstractMigration
{
    /**
     * Create user_rule table.
     * 'user_id' with 'role_id' creates unique index.
     */
    public function change()
    {
        $userRoleTable = $this->table('user_role', ['signed' => false]);

        $userRoleTable->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('role_id', 'integer', ['signed' => false])
            ->addColumn('created_at', 'datetime')
            ->addIndex(['user_id', 'role_id'], ['name' => 'idx_user_role_unique', 'unique' => true])
            ->addForeignKey('role_id', 'role', 'id', ['delete' => 'RESTRICT', 'constraint' => 'fk_user_role_role'])
            ->create();
    }
}
