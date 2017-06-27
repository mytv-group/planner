<?php

class Heap extends CApplicationComponent
{
    public $file;
    public $folder;
    public $fileToFolder;

    private function getFile()
    {
        return $this->file->__invoke();
    }

    private function getFolder()
    {
        return $this->folder->__invoke();
    }

    private function getFileToFolder()
    {
        return $this->fileToFolder->__invoke();
    }

    public function getHeapContent($folderId, $user)
    {
        $rootFiles = [];
        if ($folderId === 0) {
            $filesToFolder = $this->getFileToFolder()->findAllByAttributes([
                'id_folder' => $folderId,
                'id_author' => $user
            ]);

            foreach ($filesToFolder as $item) {
                $file = $item->file;
                $rootFiles[] = [
                    'id' => intval($file->id) * -1,
                    'text' => substr($file->name, 13, strlen($file->name) - 13),
                    'type' => 'file',
                    'mime' => $file->mime,
                    'link' => $file->link,
                    'parent' => 0,
                ];
            }
        }

        $folders = $this->getFolder()->findAllByAttributes(['author' => $user]);

        $d = [];
        foreach ($folders as $folder) {
            $d[] = [
                'id' => intval($folder->id),
                'text' => $folder->name,
                'type' => 'folder',
                'parent' => intval($folder->path),
            ];

            $files = $folder->files;
            foreach ($files as $file) {
                $d[] = [
                    'id' => intval($file->id) * -1,
                    'text' => substr($file->name, 13, strlen($file->name) - 13),
                    'type' => 'file',
                    'mime' => $file->mime,
                    'link' => $file->link,
                    'parent' => intval($folder->id),
                ];
            }
        }

        return array_merge($d, $rootFiles);
    }

    public function getFolderContent($folderId, $user)
    {
        $items = [];

        $folders = $this->getFolder()->findAllByAttributes([
            'path' => $folderId,
            'author' => $user
        ]);

        foreach ($folders as $folder) {
            $items[] = [
                'id' => intval($folder->id),
                'text' => $folder->name,
                'type' => 'folder',
                'parent' => intval($folder->path),
            ];
        }

        $filesToFolder = $this->getFileToFolder()->findAllByAttributes([
            'id_folder' => $folderId,
            'id_author' => $user
        ]);

        foreach ($filesToFolder as $item) {
            $file = $item->file;
            $items[] = [
                'id' => intval($file->id) * -1,
                'text' => substr($file->name, 13, strlen($file->name) - 13),
                'type' => 'file',
                'mime' => $file->mime,
                'link' => $file->link,
                'parent' => 0,
            ];
        }

        return $items;
    }
}
