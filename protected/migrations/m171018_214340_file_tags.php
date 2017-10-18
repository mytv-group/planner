<?php

class m171018_214340_file_tags extends CDbMigration
{
    public function up()
    {
        $ii = 0;
        $tagsTable = Yii::app()->db->schema->getTable('tag');
        $tagsToFilesTable = Yii::app()->db->schema->getTable('tag_to_file');

        if (!isset($tagsTable)) {
            $this->createTable('tag', [
                'id' => 'pk',
                'name' => 'varchar(255) NOT NULL',
                'dt' => 'timestamp NULL DEFAULT CURRENT_TIMESTAMP',
                'id_user' => 'int(11) NOT NULL',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->createIndex(
                'idx-tag-name',
                'tag',
                'name'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->createIndex(
                'idx-tag-id_user',
                'tag',
                'id_user'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->addForeignKey(
                'fk-tag-id_user',
                'tag',
                'id_user',
                'user',
                'id',
                'NO ACTION'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }

        if (!isset($tagsToFilesTable)) {
            $this->createTable('tag_to_file', [
                'id' => 'pk',
                'id_tag' => 'int(11) NOT NULL',
                'id_file' => 'bigint(20) NOT NULL',
                'dt' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->createIndex(
                'idx-tag_to_file-id_tag',
                'tag_to_file',
                'id_tag'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->addForeignKey(
                'fk-tag_to_file-id_tag',
                'tag_to_file',
                'id_tag',
                'tag',
                'id',
                'CASCADE'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->createIndex(
                'idx-tag_to_file-id_file',
                'tag_to_file',
                'id_file'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;

            $this->addForeignKey(
                'fk-tag_to_file-id_file',
                'tag_to_file',
                'id_file',
                'file',
                'id',
                'CASCADE'
            );

            $ii++;
            echo $ii;
            echo PHP_EOL.PHP_EOL;
        }
    }

    public function down()
    {
        echo "m171018_214340_file_tags does not support migration down.\n";
        return false;
    }
}
