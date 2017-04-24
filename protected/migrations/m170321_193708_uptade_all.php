<?php

class m170321_193708_uptade_all extends CDbMigration
{
    public function up()
    {
      $pointTable = Yii::app()->db->schema->getTable('point');

      if (isset($pointTable)
        && !isset($pointTable->columns['id_user'])
        && isset($pointTable->columns['sync'])
      ) {
          $this->addColumn('point', 'id_user', 'int(11) NOT NULL AFTER `sync`');
      }

      $userTable = Yii::app()->db->schema->getTable('point');

      if (isset($pointTable)
        && isset($userTable)
        && isset($pointTable->columns['username'])
        && isset($pointTable->columns['id_user'])
        && isset($userTable->columns['username'])
        && isset($userTable->columns['id'])
      ) {
          // no way to implement update join Yii::app()->db->createCommand()
          // http://stackoverflow.com/questions/8180720/use-join-and-where-with-update
          $this->execute('UPDATE `point`
            LEFT JOIN `user` ON `point`.`username`= `user`.`username` COLLATE utf8_unicode_ci
            SET `point`.`id_user`  = `user`.`id`');
      }

      if (isset($pointTable)
        && isset($pointTable->columns['tv_schedule_blocks'])
      ) {
          $this->execute('ALTER TABLE `point` DROP `tv_schedule_blocks`');
      }

      $tvscheduleTable = Yii::app()->db->schema->getTable('tvschedule');
      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['point_id'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` CHANGE `point_id` `id_point` INT(11) NOT NULL');
      }

      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['from'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` CHANGE `from` `dt_from` DATETIME NOT NULL');
      }

      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['to'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` CHANGE `to` `dt_to` DATETIME NOT NULL');
      }

      if (isset($tvscheduleTable)
        && !isset($tvscheduleTable->columns['id_user'])
        && isset($tvscheduleTable->columns['dt_to'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` ADD `id_user` INT NOT NULL AFTER `dt_to`, ADD INDEX (`id_user`)');
      }

      if (isset($tvscheduleTable)
        && isset($userTable)
        && isset($tvscheduleTable->columns['author'])
        && isset($tvscheduleTable->columns['id_user'])
        && isset($userTable->columns['username'])
        && isset($userTable->columns['id'])
      ) {
          $this->execute('UPDATE `tvschedule`
            LEFT JOIN `user` ON `tvschedule`.`author`= `user`.`username` COLLATE utf8_unicode_ci
            SET `tvschedule`.`id_user`  = `user`.`id`');
      }

      if (isset($tvscheduleTable)
        && isset($tvscheduleTable->columns['author'])
      ) {
          $this->execute('ALTER TABLE `tvschedule` DROP `author`');
      }

      $netTable = Yii::app()->db->schema->getTable('net');

      if (isset($netTable)) {
          $this->execute('DELETE FROM `net` WHERE 1');
      }

      if (isset($netTable)
        && isset($netTable->columns['user_id'])
      ) {
          $this->execute('ALTER TABLE `net` CHANGE `user_id` `id_user` INT(11) NOT NULL');
      }

      if (isset($netTable)
        && isset($netTable->foreignKeys['screen_id'])
      ) {
          $this->dropForeignKey('net_ibfk_2', 'net');
      }

      if (isset($netTable)
        && isset($netTable->columns['screen_id'])
      ) {
          $this->execute('ALTER TABLE `net` DROP `screen_id`');
      }

      if (isset($netTable)
        && !isset($netTable->columns['dt_created'])
      ) {
          $this->execute('ALTER TABLE `net` ADD `dt_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');
      }

      if (Yii::app()->db->schema->getTable('net_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `net_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
      }

      if (Yii::app()->db->schema->getTable('playlist_to_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `playlist_to_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
      }

      if (Yii::app()->db->schema->getTable('playlist_to_net_channel', true) !== null) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DROP TABLE `playlist_to_net_channel`');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
      }

      $channelTable = Yii::app()->db->schema->getTable('channel');
      if (isset($channelTable)) {
          $this->execute('SET FOREIGN_KEY_CHECKS=0;');
          $this->execute('DELETE FROM `channel` WHERE `window_id` IS NULL');
          $this->execute('SET FOREIGN_KEY_CHECKS=1;');
      }

      if (isset($channelTable)
        && isset($channelTable->foreignKeys['window_id'])
      ) {
          $this->dropForeignKey('channel_ibfk_2', 'channel');
      }

      if (isset($channelTable)
        && isset($channelTable->columns['window_id'])
      ) {
          $this->execute('ALTER TABLE `channel` CHANGE `window_id` `id_window` INT(11) NOT NULL');
      }

      if (isset($netTable)
        && !isset($netTable->foreignKeys['id_window'])
      ) {
          $this->addForeignKey('FK_showcase_window',
              'channel',
              'id_window',
              'window',
              'id',
              'CASCADE',
              'CASCADE'
          );
      }

      if (isset($channelTable)
        && isset($channelTable->columns['internalId'])
      ) {
          $this->execute('ALTER TABLE `channel` CHANGE `internalId` `id_widget` INT(11) NOT NULL');
      }

      $widgetTable = Yii::app()->db->schema->getTable('widget_to_channel');

      if (isset($channelTable)
        && isset($widgetTable)
        && isset($channelTable->columns['id'])
        && isset($channelTable->columns['id_widget'])
        && isset($widgetTable->columns['channel_id'])
        && isset($widgetTable->columns['id'])
      ) {
          $this->execute('UPDATE `channel`
            LEFT JOIN `widget_to_channel` ON `channel`.`id`= `widget_to_channel`.`channel_id`
            SET `channel`.`id_widget`  = `widget_to_channel`.`widget_id`');
      }

      if (isset($channelTable)) {
          $this->execute('ALTER TABLE `channel` RENAME `showcase`');
      }

      if (isset($widgetTable)) {
          $this->execute('DROP TABLE `widget_to_channel`');
      }
    }

    public function down()
    {
      echo "m170321_193708_uptade_all does not support migration down.\n";
      return false;
    }
}
