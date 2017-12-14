<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateRolePermission
 */
class CreateRolePermission extends AbstractMigration
{
    /**
     * Create 'role_permission' table in database.
     * 'role_id' with 'permission_id' creates unique index.
     */
    public function change()
    {
        $rolePermissionTable = $this->table('role_permission', ['signed' => false]);

        $rolePermissionTable->addColumn('role_id', 'integer', ['signed' => false])
            ->addColumn('permission_id', 'integer', ['signed' => false])
            ->addColumn('created_at', 'datetime')
            ->addIndex(['role_id', 'permission_id'], ['name' => 'idx_role_permission_unique', 'unique' => true])
            ->addForeignKey('role_id', 'role', 'id', ['delete' => 'RESTRICT', 'constraint' => 'fk_role_permission_role'])
            ->addForeignKey('permission_id', 'permission', 'id', ['delete' => 'RESTRICT', 'constraint' => 'fk_role_permission_permission'])
            ->create();
    }
}
