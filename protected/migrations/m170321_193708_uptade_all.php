<?php

class m170321_193708_uptade_all extends CDbMigration
{
    public function up()
    {
      $this->addColumn('point', 'id_user', 'int(11) NOT NULL AFTER `sync`');

      // no way to implement update join Yii::app()->db->createCommand()
      // http://stackoverflow.com/questions/8180720/use-join-and-where-with-update
      $this->execute('UPDATE `point`
        LEFT JOIN `user` ON `point`.`username`= `user`.`username` COLLATE utf8_unicode_ci
        SET `point`.`id_user`  = `user`.`id`');

      $this->execute('ALTER TABLE `point` DROP `tv_schedule_blocks`');

      $this->execute('ALTER TABLE `tvschedule` CHANGE `point_id` `id_point` INT(11) NOT NULL');

      $this->execute('ALTER TABLE `tvschedule` CHANGE `from` `dt_from` DATETIME NOT NULL');

      $this->execute('ALTER TABLE `tvschedule` CHANGE `to` `dt_to` DATETIME NOT NULL');

      $this->execute('ALTER TABLE `tvschedule` ADD `id_user` INT NOT NULL AFTER `dt_to`, ADD INDEX (`id_user`)');

      $this->execute('UPDATE `tvschedule`
        LEFT JOIN `user` ON `tvschedule`.`author`= `user`.`name` COLLATE utf8_unicode_ci
        SET `tvschedule`.`id_user`  = `user`.`id`');

      $this->execute('ALTER TABLE `tvschedule` DROP `author`');

      $this->execute('DELETE FROM `net` WHERE 1');

      $this->execute('ALTER TABLE `net` CHANGE `user_id` `id_user` INT(11) NOT NULL');

      $this->dropForeignKey('net_ibfk_2', 'net');

      $this->execute('ALTER TABLE `net` DROP `screen_id`');

      $this->execute('ALTER TABLE `net` ADD `dt_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP');

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

      $this->dropForeignKey('channel_ibfk_2', 'channel');
      $this->execute('DELETE FROM `channel` WHERE `window_id` IS NULL');
      $this->execute('ALTER TABLE `channel` CHANGE `window_id` `id_window` INT(11) NOT NULL');
      $this->addForeignKey('FK_showcase_window',
          'channel',
          'id_window',
          'window',
          'id',
          'CASCADE',
          'CASCADE'
      );

      $this->execute('ALTER TABLE `channel` CHANGE `internalId` `id_widget` INT(11) NOT NULL');

      $this->execute('UPDATE `channel`
      LEFT JOIN `widget_to_channel` ON `channel`.`id`= `widget_to_channel`.`channel_id`
      SET `channel`.`id_widget`  = `widget_to_channel`.`widget_id`');

      $this->execute('ALTER TABLE `channel` RENAME `showcase`');

      $this->execute('DROP TABLE `widget_to_channel`');
    }

    public function down()
    {
      echo "m170321_193708_uptade_all does not support migration down.\n";
      return false;
    }
}
