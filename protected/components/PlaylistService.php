<?php

class PlaylistService extends CApplicationComponent
{
    public $db;

    private function getDb()
    {
        return $this->db->__invoke();
    }

    public function deleteALLFilesFromPlaylist($playlistId, $files)
    {
        $relatedFiles = $files;

        //remove net relations
        $connection = $this->getDb();
        $connection->active=true;

        foreach ($relatedFiles as $key => $fileId) {
            //if file visibilyty 0 (only for current pl), delete it
            $sql = "SELECT `path`, `visibility` FROM `file` WHERE `id` = ".$fileId.";";
            $command = $connection->createCommand($sql);
            $dataReader=$command->query();
            $visibility = 0;
            $path = '';
            if (($row=$dataReader->read()) !== false) {
                $visibility = $row['visibility'];
                $path = $row['path'];
            }

            if ($visibility == 0) {
                $sql = "DELETE FROM `file` WHERE " .
                        "`id` = " . $fileId . ";";
                $command = Yii::app()->db->createCommand($sql);
                $execution = $command->execute();

                if (file_exists($path)){
                    try {
                        unlink($path);
                    } catch (Exception $e) {
                        error_log("Unlink failed. Exception - " . json_encode($e).
                        "Path - " . $path
                        );
                    }
                }
            }
        }
        $connection->active=false;

        return;
    }
}
