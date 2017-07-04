<?php

class m170523_195940_file_to_playlist extends CDbMigration
{
  public function up()
  {
    $ii = 0;

    $fileToPlaylistTable = Yii::app()->db->schema->getTable('file');
    if (!isset($fileToPlaylistTable)) {
        $this->execute('ALTER TABLE `file` CHANGE `id` `id` INT NOT NULL AUTO_INCREMENT;');
        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;
    }

    $fileToPlaylistTable = Yii::app()->db->schema->getTable('file_to_playlist');
    if (!isset($fileToPlaylistTable)) {
        $this->execute('CREATE TABLE `file_to_playlist` (
             `id` int(11) NOT NULL AUTO_INCREMENT,
             `id_file` bigint(20) NOT NULL,
             `id_playlist` int(11) NOT NULL,
             `order` int(11) NOT NULL,
             PRIMARY KEY (`id`),
             KEY `id_file` (`id_file`),
             KEY `id_playlist` (`id_playlist`)
           ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;');

        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;

        $this->execute('ALTER TABLE `file_to_playlist`
            ADD CONSTRAINT `file_fk` FOREIGN KEY (`id_file`)
            REFERENCES `file`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');

        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;

        $this->execute('ALTER TABLE `file_to_playlist`
            ADD CONSTRAINT `playlist_fk` FOREIGN KEY (`id_playlist`)
            REFERENCES `playlists`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');

        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;
    }

    $rows = Yii::app()->db->createCommand()
        ->select('id, files')
        ->from('playlists')
        ->queryAll();

    $ii++;
    echo $ii;
    echo PHP_EOL.PHP_EOL;

    foreach ($rows as $row) {
        $playlistId =  intval($row['id']);
        $files = explode(',', $row['files']);

        $order = 0;
        foreach ($files as $fileId) {
            $fileId = intval($fileId);
            $fileInstance = Yii::app()->db->createCommand()
                ->select('id')
                ->from('file')
                ->where('id=:id', [':id'=>$fileId])
                ->queryAll();

            if (count($fileInstance) === 1) {
              Yii::app()->db->createCommand()
                ->insert('file_to_playlist', array(
                    'id_file' => $fileId,
                    'id_playlist' => $playlistId,
                    'order' => $order
                ));
                $order++;
            }

            echo 'FILE_ID: ' . $fileId;
            echo PHP_EOL.PHP_EOL;
        }
    }

    $fileTable = Yii::app()->db->schema->getTable('file');
    $ii = 0;

    if (isset($fileTable)
      && !isset($fileTable->columns['id_user'])
    ) {
        $this->addColumn('file', 'id_user', 'int(11) NOT NULL AFTER `author`');
        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;
    }

    $fileTable = Yii::app()->db->schema->getTable('file');
    $fileTableColumns = $fileTable->getColumnNames();
    $userTable = Yii::app()->db->schema->getTable('user');

    if (isset($fileTable)
      && isset($userTable)
    ) {
        try {
          $this->execute('UPDATE `file`
            JOIN `user` ON `file`.`author`= `user`.`username` COLLATE utf8_unicode_ci
            SET `file`.`id_user`  = `user`.`id`');
        } catch(Exception $e) {}
        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;
    }

    if (isset($fileTable) && isset($fileTableColumns['author'])) {
        $this->dropColumn('file', 'author');
        $ii++;
        echo $ii;
        echo PHP_EOL.PHP_EOL;
    }
  }

  public function down()
  {
    echo "m170523_195940_file_to_playlist does not support migration down.\n";
    return false;
  }
}
