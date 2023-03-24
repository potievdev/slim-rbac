<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class CreatePermission
 */
class CreatePermission extends AbstractMigration
{
    /**
     * Create permission table in database.
     * Field 'name' is unique index.
     */
    public function change()
    {
        $permissionTable = $this->table('permission', ['signed' => false]);

        $permissionTable->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('status', 'boolean', ['default' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex('name', ['name' => 'idx_permission_name' ,'unique' => true])
            ->create();
    }
}
