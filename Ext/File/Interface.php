<?php

/**
 * Filesystem access
 */
interface Ext_File_Interface
{
    public function isDir($pathname);

    public function mkdir($path, $mode = 0777, $recursive = false);

    /**
     * @param $src string
     * @param $dest string
     */
    public function rename($src, $dest);

    /**
     * @param $src string
     * @param $dest string
     */
    public function copy($src, $dest);

    /**
     * Move file from local filesystem to fsAccessObject filesystem
     * 
     */
    public function create($src, $dest);

    public function delete($filepath);
}