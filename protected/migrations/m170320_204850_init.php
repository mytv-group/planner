<?php

class m170320_204850_init extends CDbMigration
{
  public function up()
  {
      if (Yii::app()->db->schema->getTable('channel', true) === null) {
          $this->createTable('channel', [
              'id' => 'pk',
              'id_point' => 'int(11) NOT NULL',
              'window_id' => 'int(11) NOT NULL',
              'internalId' => 'int(11) NOT NULL',
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('point', true) === null) {
          $this->createTable('point', [
              'id' => 'pk',
              'name' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL',
              'username' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL',
              'password' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL',
              'ip' => 'varchar(100) NOT NULL',
              'sync_time' => 'datetime DEFAULT NULL',
              'update_time' => 'datetime DEFAULT NULL',
              'volume' => 'smallint(11) NOT NULL',
              'TV' => 'tinyint(1) NOT NULL',
              'tv_schedule_blocks' => 'text NOT NULL',
              'screen_id' => 'int(11) DEFAULT NULL',
              'sync' => 'tinyint(1) NOT NULL',
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('file', true) === null) {
          $this->createTable('file', [
              'id' => 'pk',
              'name' => 'text NOT NULL',
              'duration' => 'varchar(20) NOT NULL',
              'mime' => 'varchar(100) NOT NULL',
              'path' => 'varchar(255) NOT NULL',
              'link' => 'varchar(255) NOT NULL',
              'date_created' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
              'visibility' => 'tinyint(1) NOT NULL',
              'author' => 'varchar(255) NOT NULL',
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('file_to_folder', true) === null) {
          $this->createTable('file_to_folder', [
              'id' => 'pk',
              'id_file' => 'int(11) NOT NULL',
              'id_folder' => 'int(11) NOT NULL',
              'id_author' => 'varchar(100) NOT NULL',
              'dt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('folder', true) === null) {
          $this->createTable('folder', [
              'id' => 'pk',
              'name' => 'varchar(255) NOT NULL',
              'path' => 'int(11) NOT NULL',
              'author' => 'varchar(255) NOT NULL',
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('net', true) === null) {
          $this->createTable('net', [
              'id' => 'pk',
              'name' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL',
              'screen_id' => 'int(11) DEFAULT NULL',
              'user_id' => 'int(11) NOT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('playlists', true) === null) {
          $this->createTable('playlists', [
              'id' => 'pk',
              'name' => 'varchar(100) NOT NULL',
              'files' => 'text NOT NULL',
              'fromDatetime' => 'date NOT NULL',
              'toDatetime' => 'date NOT NULL',
              'fromTime' => 'time NOT NULL',
              'toTime' => 'time NOT NULL',
              'type' => 'tinyint(4) NOT NULL',
              'every' => 'time NOT NULL',
              'sun' => 'tinyint(1) NOT NULL',
              'mon' => 'tinyint(1) NOT NULL',
              'tue' => 'tinyint(1) NOT NULL',
              'wed' => 'tinyint(1) NOT NULL',
              'thu' => 'tinyint(1) NOT NULL',
              'fri' => 'tinyint(1) NOT NULL',
              'sat' => 'tinyint(1) NOT NULL',
              'author' => 'varchar(255) NOT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('playlist_to_point', true) === null) {
          $this->createTable('playlist_to_point', [
              'id' => 'pk',
              'id_point' => 'int(11) NOT NULL',
              'id_playlist' => 'int(11) NOT NULL',
              'channel_type' => 'int(11) NOT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('point_to_user', true) === null) {
          $this->createTable('point_to_user', [
              'id' => 'pk',
              'point_id' => 'int(11) NOT NULL',
              'user_id' => 'int(11) NOT NULL',
              'time' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('screen', true) === null) {
          $this->createTable('screen', [
              'id' => 'pk',
              'name' => 'varchar(255) NOT NULL',
              'width' => 'mediumint(9) NOT NULL',
              'height' => 'mediumint(9) NOT NULL',
              'user_id' => 'int(11) DEFAULT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('statistic', true) === null) {
          $this->createTable('statistic', [
              'id' => 'pk',
              'duration' => 'float NOT NULL',
              'channel' => 'int(11) NOT NULL',
              'file_name' => 'varchar(255) NOT NULL',
              'id_point' => 'int(11) NOT NULL',
              'id_file' => 'int(11) NOT NULL',
              'id_playlist' => 'int(11) NOT NULL',
              'id_author' => 'int(11) NOT NULL',
              'dt_playback' => 'datetime NOT NULL',
              'dt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('stream', true) === null) {
          $this->createTable('stream', [
              'id' => 'pk',
              'playlist_id' => 'int(10) NOT NULL',
              'url' => 'varchar(255) NOT NULL',
              'created_dt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('tvschedule', true) === null) {
          $this->createTable('tvschedule', [
              'id' => 'pk',
              'point_id' => 'int(11) NOT NULL',
              'from' => 'datetime NOT NULL',
              'to' => 'datetime NOT NULL',
              'author' => 'tinytext NOT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('user', true) === null) {
          $this->createTable('user', [
              'id' => 'pk',
              'username' => 'varchar(100) NOT NULL',
              'password' => 'varchar(255) NOT NULL',
              'name' => 'varchar(255) NOT NULL',
              'blocked' => "int(1) NOT NULL DEFAULT '0'",
              'role' => 'text NOT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('widget', true) === null) {
          $this->createTable('widget', [
              'id' => 'pk',
              'name' => 'varchar(255) NOT NULL',
              'description' => 'text NOT NULL',
              'show_duration' => 'int(11) NOT NULL',
              'periodicity' => 'int(11) NOT NULL',
              'offset' => "int(11) NOT NULL DEFAULT '0'",
              'config' => 'text NOT NULL',
              'created_dt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('widget_to_channel', true) === null) {
          $this->createTable('widget_to_channel', [
              'id' => 'pk',
              'widget_id' => 'int(11) NOT NULL',
              'channel_id' => 'int(11) NOT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      if (Yii::app()->db->schema->getTable('window', true) === null) {
          $this->createTable('window', [
              'id' => 'pk',
              'screen_id' => 'int(11) DEFAULT NULL',
              'name' => 'varchar(25) NOT NULL',
              'top' => 'int(11) NOT NULL',
              'left' => 'int(11) NOT NULL',
              'width' => 'int(11) NOT NULL',
              'height' => 'int(11) NOT NULL',
              'authorId' => 'int(11) DEFAULT NULL'
          ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
      }

      /*$this->addForeignKey('FK_channel_point',
          'channel',
          'id_point',
          'point',
          'id',
          'CASCADE',
          'NO ACTION'
      );*/
  }

  public function down()
  {
      //$this->dropForeignKey('FK_channel_point', 'channel');

      $this->dropTable('channel');
      $this->dropTable('point');
      $this->dropTable('file');
      $this->dropTable('file_to_folder');
      $this->dropTable('folder');
      $this->dropTable('net');
      $this->dropTable('playlists');
      $this->dropTable('playlist_to_point');
      $this->dropTable('point_to_user');
      $this->dropTable('screen');
      $this->dropTable('statistic');
      $this->dropTable('stream');
      $this->dropTable('tvschedule');
      $this->dropTable('user');
      $this->dropTable('widget');
      $this->dropTable('widget_to_channel');
      $this->dropTable('window');

      return true;
  }
}
