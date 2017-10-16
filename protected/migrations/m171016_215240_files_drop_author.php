<?php

class m171016_215240_files_drop_author extends CDbMigration
{
    public function up()
    {
        $ii = 0;
        $fileTable = Yii::app()->db->schema->getTable('file');
        $playlistsTable = Yii::app()->db->schema->getTable('playlists');

        if (isset($fileTable)
          && !isset($fileTable->columns['size'])
        ) {
            $this->addColumn('file', 'size', 'INT NOT NULL DEFAULT \'0\' AFTER `duration`');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($fileTable)
          && isset($fileTable->columns['author'])
        ) {
            $this->dropColumn('file', 'author');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($playlistsTable)
            && isset($playlistsTable->columns['author'])
        ) {
            $this->dropColumn('playlists', 'author');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($playlistsTable)) {
            try {
                $this->execute('UPDATE `file` SET `name` = SUBSTRING(`name`, 14)');
            } catch(Exception $e) {}
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }
    }

    public function down()
    {
        echo "m171016_215240_files_drop_author does not support migration down.\n";
        return false;
    }
}
