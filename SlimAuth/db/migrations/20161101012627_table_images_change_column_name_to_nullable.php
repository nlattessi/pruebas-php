<?php

use Phinx\Migration\AbstractMigration;

class TableImagesChangeColumnNameToNullable extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('images');
        $users->changeColumn('name', 'string', ['null' => true])
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
