<?php

class FolderRepository extends BaseComponent
{
    public $heap;
    public $fileToFolder;

    public function getFolderContent($folderId, $user)
    {
        $items = [];

        $folders = $this->model()->findAllByAttributes([
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
            if (isset($item->file)) {
                $file = $item->file;
                $tags = $file->tags;

                $tagsArray = [];
                if (is_array($tags)) {
                    foreach ($tags as $tag) {
                        $tagsArray[] = $tag->name;
                    }
                }

                $items[] = [
                    'id' => intval($file->id),
                    'text' => $file->name,
                    'duration' => $file->duration,
                    'size' => $file->size,
                    'type' => 'file',
                    'mime' => $file->mime,
                    'link' => $file->link,
                    'parent' => 0,
                    'tags' => $tagsArray
                ];
            } else {
                $item->delete();
            }
        }

        return $items;
    }

    public function deleteFolder($folderId, $user)
    {
        $heap = $this->getHeap();

        $content = $heap->getHeapContent($user->username);
        $tree = $heap->buildTree($content);
        $children = $heap->findBranch($tree, $folderId);
        $flat = $heap->treeToFlat($children);

        foreach ($flat as $item) {
            if ($item['type'] === 'file') {
                $this->deleteFile($item['id'], $user);
            }

            if ($item['type'] === 'folder') {
                $this->model()->deleteAllByAttributes([
                    'id' => $item['id'],
                    'author' => $user->username
                ]);
            }
        }

        $this->model()->deleteAllByAttributes([
            'id' => $folderId,
            'author' => $user->username
        ]);
    }
}
