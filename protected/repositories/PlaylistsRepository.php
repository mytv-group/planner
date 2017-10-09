<?php

class PlaylistsRepository extends BaseRepository
{
    public $user;

    private function getUser()
    {
        return $this->user->__invoke();
    }

    public function deletePlaylistFiles($playlistId)
    {
        $playlistModel = $this->model();
        $playlist = $playlistModel->findByPk($playlistId);

        if ($playlist && $playlist->filesToPlaylist) {
            $filesToPlaylist = $playlist->filesToPlaylist;
            foreach ($filesToPlaylist as $item) {
                $item->delete();
            }
        }

        if ($playlist && $playlist->relatedFiles) {
            $files = $playlist->relatedFiles;
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
        }

        return;
    }

    public function getUserPlaylists()
    {
        $playlistModel = $this->model();
        $user = $this->getUser();

        if ($user->isAdmin()) {
            return $playlistModel::model()->findAll();
        } else {
            return $playlistModel::model()->findAllByAttributes(['id_user' => $user->id]);
        }
    }

    public function searchByExpiration($expirationTo, $expirationFrom = null)
    {
        $user = $this->getUser();

        $criteria=new CDbCriteria;
        if ($user->isAdmin()) {
            $criteria->compare('id_user', $user->id);
        }
        $expirationToExpression = new CDbExpression($expirationTo);
        $criteria->addCondition('`toDatetime` < '.$expirationToExpression);
        if ($expirationFrom !== null) {
            $expirationFromExpression = new CDbExpression($expirationFrom);
            $criteria->addCondition('`toDatetime` >= '.$expirationFromExpression);
        }
        return new CActiveDataProvider($this->model(), [
            'criteria'=>$criteria,
        ]);
    }
}
