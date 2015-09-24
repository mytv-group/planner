<?

$x = ($file->id_net) ? $file->id_net : $file->id_camp;
$path = realpath(Yii::app()->basePath."/../../spool/");

    if ($file->type == 'v'){
        $this->widget ( 'ext.mediaElement.MediaElementPortlet',
            array (
                'url' => "/preview/".$file->link,
                'mimeType' =>$file->mime,
                'autoplay'=>false,
                'height'=>100
            )
        );
    }
    elseif($file->type == 'a') {
        $this->widget ( 'ext.mediaElement.MediaElementPortlet',
            array (
                'url' => "/preview/".$file->link,
                'mimeType' =>$file->mime,
                'autoplay'=>false,
                'height'=>100
            )
        );
    }
    elseif($file->type == 'i') {
        echo CHtml::image("/preview/".$file->link, $file->name, array('height'=>100));
    }
    else {
        echo CHtml::image(Yii::app()->request->baseUrl."/images/nopreview.png", $file->name, array('height'=>100));
    }


?>