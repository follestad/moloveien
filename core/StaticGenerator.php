<?php

namespace Core;

class StaticGenerator {

    private string $root = '';
    private string $source = '';




    public function setRootDirectory(string $dir) {
        $this->root = $dir;
    }




    public function setSourceFolder(string $folder) {
        $this->source = $this->root . $folder;
    }


}