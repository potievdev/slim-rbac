<?php


use Phinx\Migration\AbstractMigration;

class CreateRoleHierarchy extends AbstractMigration
{
    /**
     * Create 'role_hierarchy' table
     * 'parent_role_id' with 'child_role_id' creates unique index.
     */
    public function change()
    {
        $userRoleTable = $this->table('role_hierarchy', ['signed' => false]);

        $userRoleTable->addColumn('parent_role_id', 'integer', ['signed' => false])
            ->addColumn('child_role_id', 'integer', ['signed' => false])
            ->addColumn('created_at', 'datetime')
            ->addIndex(['parent_role_id', 'child_role_id'], ['name' => 'idx_role_hierarchy_unique', 'unique' => true])
            ->addForeignKey('parent_role_id', 'role', 'id', ['delete' => 'RESTRICT', 'constraint' => 'fk_role_hierarchy_parent'])
            ->addForeignKey('child_role_id', 'role', 'id', ['delete' => 'RESTRICT', 'constraint' => 'fk_role_hierarchy_child'])
            ->create();
    }
}
