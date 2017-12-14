<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateRole
 */
class CreateRole extends AbstractMigration
{
    /**
     * Create role table in database.
     * Field 'name' is unique index.
     */
    public function change()
    {
        $roleTable = $this->table('role', ['signed' => false]);

        $roleTable->addColumn('name', 'string', ['limit' => 50])
            ->addColumn('status', 'boolean', ['default' => 1])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex('name', ['name' => 'idx_role_name', 'unique' => true])
            ->create();
    }
}
