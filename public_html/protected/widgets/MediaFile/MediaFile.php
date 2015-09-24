<?php

class MediaFile extends CWidget {

    public $file;

    public function run(){
        $this->render("MediaFileView", array('file'=>$this->file));
    }
}