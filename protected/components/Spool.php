<?php

Yii::import('ext.EHttpClient.*');

class Spool extends CApplicationComponent
{
    public $file;
    public $playlistToPoint;
    public $spoolPointsPath;
    public $spoolAudioPath;
    public $spoolVideoPath;
    public $spoolImagesPath;
    public $spoolOtherPath;

    private function getFile()
    {
        return $this->file->__invoke();
    }

    private function getPlaylistToPoint()
    {
        return $this->playlistToPoint->__invoke();
    }

    private function getPointsPath()
    {
        return $this->spoolPointsPath;
    }

    private function getAudioPath()
    {
        return $this->spoolAudioPath;
    }

    private function getVideoPath()
    {
        return $this->spoolVideoPath;
    }

    private function getImagesPath()
    {
        return $this->spoolImagesPath;
    }

    private function getOtherPath()
    {
        return $this->spoolOtherPath;
    }

    public function removeSpoolPath($id)
    {
        $pointDir = $this->getPointsPath() . $id;
        if (file_exists($pointDir)) {
            try {
                $this->deleteDir($pointDir);
            } catch (Exception $e) {
                error_log("Unlink failed. Exception - " . json_encode($e).
                    "Dir - " . $pointDir
                );
            }
        }
    }

    private function deleteDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir") {
                        $this->deleteDir($dir.DIRECTORY_SEPARATOR.$object);
                    } else {
                        unlink($dir.DIRECTORY_SEPARATOR.$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function prepareFilesForSync($id)
    {
        $fileModel = $this->getFile();
        $pointDir = $this->getPointsPath() . $id;

        //remove dir if exist
        if(file_exists($pointDir)) {
            try {
                $this->deleteDir($pointDir);
            } catch (Exception $e) {
                error_log("Unlink failed. Exception - " . json_encode($e).
                "Dir - " . $pointDir);
            }
        }
        $playlistToPointModel = $this->getPlaylistToPoint();
        $avaliablePlaylists = $playlistToPointModel->findAllByAttributes([
            'id_point' => $id
        ]);

        foreach ($avaliablePlaylists as $playlistToPoint) {
            $pl = $playlistToPoint->playlist;
            $channelDir = $pointDir . DIRECTORY_SEPARATOR . $playlistToPoint->channel_type;
            $channelFullDir = $this->prepareSpoolPath($channelDir);

            $plFiles = explode(",", $pl->files);

            foreach ($plFiles as $fileId) {
                if ($fileId != '') {
                    $file = $fileModel->findByPk($fileId);

                    if (empty($file)) {
                        continue;
                    }

                    $symlinkPath = $channelFullDir . $file->name;
                    if (!file_exists($symlinkPath)
                        && file_exists($file->path)
                    ) {
                        if (defined('SYMLINK')) {
                            symlink($file->path, $symlinkPath);
                        } else {
                            copy($file->path, $symlinkPath);
                        }
                    }
                }
            }
        }
    }

    private function getSpoolPath($pathAppendix)
    {
        return $_SERVER["DOCUMENT_ROOT"] + $pathAppendix;
    }

    private function prepareSpoolPath($pathAppendix)
    {
        $pathAppendix = explode(DIRECTORY_SEPARATOR, $pathAppendix);
        $contentPath = rtrim($_SERVER["DOCUMENT_ROOT"], '/');

        foreach ($pathAppendix as $folder) {
            $contentPath .= DIRECTORY_SEPARATOR . $folder;
            if (!file_exists($contentPath) && !is_dir($contentPath)) {
                mkdir($contentPath);
            }
        }

        $contentPath .= DIRECTORY_SEPARATOR;
        return $contentPath;
    }

    public function putUploadedFile($type, $uploadFilePath, $uploadFileName)
    {
        $path = $this->getOtherPath();
        if ($type == "audio") {
          $path = $this->getAudioPath();
        } else if ($type == "video") {
          $path = $this->getVideoPath();
        }  else if ($type == "image") {
          $path = $this->getImagesPath();
        }

        $contentPath = $this->PrepareSpoolPath($path);
        $uploadFileName = str_replace(" ", "", $uploadFileName);
        $uploadFileName = uniqid() . $this->CyrillicToTransLite($uploadFileName);
        try {
            rename($uploadFilePath, $contentPath . $uploadFileName);
        } catch(Exception $e) {
            throw new Exception("Error file renaming. New name: " . $contentPath . $uploadFileName, 1);
        }

        return [
            'path' => $contentPath . $uploadFileName,
            'name' => $uploadFileName,
            'link' => $this->serverUrl() . '/' . $path . $uploadFileName
        ];
    }

    private function serverUrl()
    {
        return sprintf(
                "%s://%s",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['SERVER_NAME']
        );
    }

    private function CyrillicToTransLite($textcyr)
    {
        $cyr  = array('Р°','Р±','РІ','Рі','Рґ','e','Рµ','С”','Р¶','Р·','Рё','С–','С—','Р№','Рє','Р»','Рј','РЅ','Рѕ','Рї','СЂ','СЃ','С‚','Сѓ',
                'С„','С…','С†','С‡','С€','С‰','СЉ','СЊ', 'СЋ','СЏ','Рђ','Р‘','Р’','Р“','Р”','Р•','Р–','Р—','Р�','Р™','Рљ','Р›','Рњ','Рќ','Рћ','Рџ','Р ','РЎ','Рў','РЈ',
                'Р¤','РҐ','Р¦','Р§','РЁ','Р©','РЄ','Р¬', 'Р®','РЇ' );
        $lat = array( 'a','b','v','g','d','e','e','e','zh','z','i','i','i','y','k','l','m','n','o','p','r','s','t','u',
                'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
                'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
                'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );
        $translit = str_replace($cyr, $lat, $textcyr);

        $unwanted_array = array('Е '=>'S', 'ЕЎ'=>'s', 'ЕЅ'=>'Z', 'Еѕ'=>'z', 'ГЂ'=>'A', 'ГЃ'=>'A', 'Г‚'=>'A', 'Гѓ'=>'A', 'Г„'=>'A', 'Г…'=>'A', 'Г†'=>'A', 'Г‡'=>'C', 'Г€'=>'E', 'Г‰'=>'E',
                'ГЉ'=>'E', 'Г‹'=>'E', 'ГЊ'=>'I', 'ГЌ'=>'I', 'ГЋ'=>'I', 'ГЏ'=>'I', 'Г‘'=>'N', 'Г’'=>'O', 'Г“'=>'O', 'Г”'=>'O', 'Г•'=>'O', 'Г–'=>'O', 'Г�'=>'O', 'Г™'=>'U',
                'Гљ'=>'U', 'Г›'=>'U', 'Гњ'=>'U', 'Гќ'=>'Y', 'Гћ'=>'B', 'Гџ'=>'Ss', 'Г '=>'a', 'ГЎ'=>'a', 'Гў'=>'a', 'ГЈ'=>'a', 'Г¤'=>'a', 'ГҐ'=>'a', 'Г¦'=>'a', 'Г§'=>'c',
                'ГЁ'=>'e', 'Г©'=>'e', 'ГЄ'=>'e', 'Г«'=>'e', 'Г¬'=>'i', 'Г­'=>'i', 'Г®'=>'i', 'ГЇ'=>'i', 'Г°'=>'o', 'Г±'=>'n', 'ГІ'=>'o', 'Гі'=>'o', 'Гґ'=>'o', 'Гµ'=>'o',
                'Г¶'=>'o', 'Гё'=>'o', 'Г№'=>'u', 'Гє'=>'u', 'Г»'=>'u', 'ГЅ'=>'y', 'ГЅ'=>'y', 'Гѕ'=>'b', 'Гї'=>'y' );
        $translit = strtr($translit, $unwanted_array);

        $translit = preg_replace('/[^\p{L}\p{N}\s\.]/u', '', $translit);

        $cyr  = array('а','б','в','г','д','e','ж','з','и','й','к','л','м','н','о','п','р','с','т','у',
                'ф','х','ц','ч','ш','щ','ъ','ь', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
                'Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ь', 'Ю','Я' );

        $lat = array( 'a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
                'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a' ,'y' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
                'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
                'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );

        $translit = str_replace($cyr, $lat, $translit);

        return $translit;
    }
}
