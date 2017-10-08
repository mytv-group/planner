<?php

class FileRepository extends BaseRepository
{
    public $fileToFolder;

    private function getFileToFolder()
    {
        return $this->fileToFolder->__invoke();
    }

    public function deleteFile($fileId, $user)
    {
        $this->getFileToFolder()->deleteAllByAttributes([
            'id_file' => $fileId,
            'id_author' => $user->username
        ]);

        $files = $this->model()->findByAttributes([
            'id' => $fileId,
            'id_user' => $user->id
        ]);

        foreach ($files as $file) {
            if (isset($file->path) && file_exists($file->path)) {
                unlink($file->path);
            }
        }

        $this->model()->deleteAllByAttributes([
            'id' => $fileId,
            'id_user' => $user->id
        ]);
    }
}
