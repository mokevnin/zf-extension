<?php

/**
 * Filesystem access
 */
interface Ext_File_Interface
{
    public function isDir($pathname);

    /**
     * Что может означать mode в контексте webdav?
     * При включенной опции nginx create_full_put_path, recursive не имеет смысла, но разработчик
     * сам должен это учитывать
     *
     */
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
}