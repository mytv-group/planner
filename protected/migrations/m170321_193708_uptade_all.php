<?php

class m170321_193708_uptade_all extends CDbMigration
{
    public function up()
    {
      $pointTable = Yii::app()->db->schema->getTable('point');
      $ii = 0;

      if (isset($pointTable)
        && !isset($pointTable->columns['id_user'])
        && isset($pointTable->columns['sync'])
      ) {
          $this->addColumn('point', 'id_user', 'int(11) NOT NULL AFTER `sync`');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      $pointTable = Yii::app()->db->schema->getTable('point');
      $userTable = Yii::app()->db->schema->getTable('user');

      if (isset($pointTable)
        && isset($userTable)
      ) {
          try {
            // no way to implement update join Yii::app()->db->createCommand()
            // http://stackoverflow.com/questions/8180720/use-join-and-where-with-update
            $this->execute('UPDATE `point`
              LEFT JOIN `user` ON `point`.`username`= `user`.`username` COLLATE utf8_unicode_ci
              SET `point`.`id_user`  = `user`.`id`');
          } catch(Exception $e) {}
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($pointTable)
        && isset($pointTable->columns['tv_schedule_blocks'])
      ) {
          $this->execute('ALTER TABLE `point` DROP `tv_schedule_blocks`');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      $tvscheduleTable = Yii::app()->db->schema->getTable('tvschedule');
      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['point_id'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` CHANGE `point_id` `id_point` INT(11) NOT NULL');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['from'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` CHANGE `from` `dt_from` DATETIME NOT NULL');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['to'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` CHANGE `to` `dt_to` DATETIME NOT NULL');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($tvscheduleTable)) {
          $this->execute('ALTER TABLE `tvschedule` ADD `id_user` INT NOT NULL AFTER `dt_to`, ADD INDEX (`id_user`)');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      $tvscheduleTable = Yii::app()->db->schema->getTable('tvschedule');

      if (isset($tvscheduleTable)) {
          try {
              $this->execute('UPDATE `tvschedule`
                LEFT JOIN `user` ON `tvschedule`.`author`= `user`.`username` COLLATE utf8_unicode_ci
                SET `tvschedule`.`id_user`  = `user`.`id`');
          } catch(Exception $e) {}
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['author'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` DROP `author`');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      $netTable = Yii::app()->db->schema->getTable('net');

      if (isset($netTable)) {
          $this->execute('DELETE FROM `net` WHERE 1');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($netTable)
        && isset($netTable->columns['user_id'])
      ) {
          $this->execute('ALTER TABLE `net` CHANGE `user_id` `id_user` INT(11) NOT NULL');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($netTable)
        && isset($netTable->foreignKeys['screen_id'])
      ) {
          $this->dropForeignKey('net_ibfk_2', 'net');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($netTable)
        && isset($netTable->columns['screen_id'])
      ) {
          $this->execute('ALTER TABLE `net` DROP `screen_id`');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($netTable)
        && !isset($netTable->columns['dt_created'])
      ) {
          $this->execute('ALTER TABLE `net` ADD `dt_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (Yii::app()->db->schema->getTable('net_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `net_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (Yii::app()->db->schema->getTable('playlist_to_net_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `playlist_to_net_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      $pointToNetTable = Yii::app()->db->schema->getTable('point_to_net');

      if (isset($pointToNetTable)
        && isset($pointToNetTable->columns['point_id'])
      ) {
          $this->execute('ALTER TABLE `point_to_net` CHANGE `point_id` `id_point` INT(11) NOT NULL');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($pointToNetTable)
        && isset($pointToNetTable->columns['net_id'])
      ) {
          $this->execute('ALTER TABLE `point_to_net` CHANGE `net_id` `id_net` INT(11) NOT NULL');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($pointToNetTable)
        && isset($pointToNetTable->columns['time'])
      ) {
          $this->execute('ALTER TABLE `point_to_net` CHANGE `time` `dt_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      $channelTable = Yii::app()->db->schema->getTable('channel');

      if (isset($channelTable)) {
          $this->execute('CREATE TABLE `showcase` (
               `id` int(11) NOT NULL AUTO_INCREMENT,
               `id_point` int(11) NOT NULL,
               `id_window` int(11) DEFAULT NULL,
               `id_widget` int(11) NOT NULL,
               PRIMARY KEY (`id`),
               KEY `id_point` (`id_point`),
               KEY `id_window` (`id_window`),
               KEY `id_widget` (`id_widget`),
               CONSTRAINT `showcase_ibfk_1` FOREIGN KEY (`id_point`) REFERENCES `point` (`id`) ON DELETE CASCADE,
               CONSTRAINT `showcase_ibfk_2` FOREIGN KEY (`id_window`) REFERENCES `window` (`id`) ON DELETE CASCADE,
               CONSTRAINT `showcase_ibfk_3` FOREIGN KEY (`id_widget`) REFERENCES `widget` (`id`) ON DELETE CASCADE
             ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;');

          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;

          $this->execute("INSERT INTO
              `showcase` (`id_point`,`id_window`,`id_widget`)
            SELECT
              `channel`.`id_point`,
              `channel`.`window_id`,
              `widget_to_channel`.`widget_id`
            FROM
                `channel`
            JOIN `widget_to_channel` ON `channel`.`id` = `widget_to_channel`.`channel_id`
            WHERE
                `channel`.`window_id` IS NOT NULL;");

          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (isset($channelTable)) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (Yii::app()->db->schema->getTable('playlist_to_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `playlist_to_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (Yii::app()->db->schema->getTable('widget_to_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `widget_to_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
          $ii++;
          echo $ii;
          echo PHP_EOL.PHP_EOL;
      }

      if (Yii::app()->db->schema->getTable('playlists', true) !== null) {
          $this->execute('UPDATE `playlists` SET `type`=`type` + 1 WHERE 1;');
      }
    }

    public function down()
    {
      $ii++;
      echo $ii;
      echo "m170321_193708_uptade_all does not support migration down.\n";
      return false;
    }
}
