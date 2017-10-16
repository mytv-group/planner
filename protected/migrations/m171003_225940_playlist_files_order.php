<?php

class m171003_225940_playlist_files_order extends CDbMigration
{
    public function up()
    {
        $ii = 0;
        $playlistsTable = Yii::app()->db->schema->getTable('playlists');
        $userTable = Yii::app()->db->schema->getTable('user');

        if (isset($playlistsTable)
          && !isset($playlistsTable->columns['id_user'])
        ) {
            $this->addColumn('playlists', 'id_user', 'int(11) NOT NULL AFTER `author`');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($playlistsTable) && isset($userTable)) {
            try {
                $this->execute('UPDATE `playlists`
                    JOIN `user` ON `playlists`.`author`= `user`.`username` COLLATE utf8_unicode_ci
                    SET `playlists`.`id_user`  = `user`.`id`');
            } catch(Exception $e) {}

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($playlistsTable)
          && !isset($playlistsTable->columns['files_order'])
          && isset($playlistsTable->columns['type'])
        ) {
            $this->addColumn('playlists', 'files_order', 'ENUM(\'ASC\',\'DESC\',\'RANDOM\') NOT NULL DEFAULT \'ASC\' AFTER `type`');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($playlistsTable)
            && isset($playlistsTable->columns['files'])
        ) {
            $this->dropColumn('playlists', 'files');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (isset($playlistsTable)
            && isset($playlistsTable->columns['author'])
        ) {
            $this->execute('ALTER TABLE `playlists` CHANGE `author` '
                . ' `author` VARCHAR(255) CHARACTER SET utf8 '
                . 'COLLATE utf8_general_ci NULL DEFAULT NULL');
            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

    }

    public function down()
    {
        echo "m171003_225940_playlist_files_order does not support migration down.\n";
        return false;
    }
}
