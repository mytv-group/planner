<?php

class PlaylistService extends CApplicationComponent
{
    public $playlist;

    private function getPlaylist()
    {
        return $this->playlist->__invoke();
    }

    public function deletePlaylistFiles($playlistId)
    {
        $playlistModel = $this->getPlaylist();
        $playlist = $playlistModel->findByPk($playlistId);

        if ($playlist && $playlist->filesToPlaylist) {
            $filesToPlaylist = $playlist->filesToPlaylist;
        }

        foreach ($filesToPlaylist as $item) {
            $item->delete();
        }

        $files = [];

        if ($playlist && $playlist->relatedFiles) {
            $files = $playlist->relatedFiles;
        }

        foreach ($files as $file) {
            //if file visibilyty 0 (only for current pl), delete it
            if ($file->visibility == 0) {
                $file->delete();

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

        return;
    }
}
