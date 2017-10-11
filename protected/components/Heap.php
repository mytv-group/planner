<?php

class Heap extends BaseComponent
{
    public $file;
    public $folder;
    public $fileToFolder;

    public function getHeapContent($user)
    {
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
                    'id' => intval($file->id),
                    'text' => substr($file->name, 13, strlen($file->name) - 13),
                    'type' => 'file',
                    'mime' => $file->mime,
                    'link' => $file->link,
                    'parent' => intval($folder->id),
                ];
            }
        }

        $folderId = 0;

        $filesToFolder = $this->getFileToFolder()->findAllByAttributes([
            'id_folder' => $folderId,
            'id_author' => $user
        ]);

        foreach ($filesToFolder as $item) {
            $file = $item->file;
            $d[] = [
                'id' => intval($file->id),
                'text' => substr($file->name, 13, strlen($file->name) - 13),
                'type' => 'file',
                'mime' => $file->mime,
                'link' => $file->link,
                'parent' => 0,
            ];
        }

        return $d;
    }

    function buildTree($d, $r = 0, $pk = 'parent', $k = 'id', $c = 'children')
    {
        $m = array();
        foreach ($d as $e) {
            isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
            isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
            $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
        }

        return $m[$r];//[0]; // remove [0] if there could be more than one root nodes
    }

    function findBranch($tree, $searchId, $key = 'id', $children = 'children')
    {
        $searchedBranch = [];
        foreach ($tree as $branch) {
            if ($branch[$key] === $searchId) {
                $searchedBranch = isset($branch[$children]) ? $branch[$children] : [];
                break;
            } else {
                $searchedBranch = $this->findBranch($branch[$children], $searchId);
            }
        }

        return $searchedBranch;
    }

    function treeToFlat($tree, &$flat = [], $children = 'children')
    {
        foreach ($tree as $branch) {
            $flat[] = $branch;
            $flat = array_merge($this->treeToFlat($branch[$children], $flat));
        }

        return $flat;
    }
}
