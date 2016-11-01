<?php

use Phinx\Migration\AbstractMigration;

class TableImagesRenameColumnFilepath extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('images');
        $table->renameColumn('filepath', 'filename');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('images');
        $table->renameColumn('filename', 'filepath');
    }
}
