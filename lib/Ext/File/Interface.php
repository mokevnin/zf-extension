<?php

/**
 * Filesystem access
 */
interface Ext_File_Interface
{
    /**
     * @param $pathname string
     * @return boolean
     */
    public function isDir($pathname);

    /**
     * @param $src string
     * @param $dest string
     * @return boolean
     */
    public function mkdir($path, $recursive = true);

    /**
     * @param $src string
     * @param $dest string
     * @return boolean
     */
    public function rename($src, $dest);

    /**
     * @param $src string
     * @param $dest string
     * @return boolean
     */
    public function copy($src, $dest);

    /**
     * Move file from local filesystem to fsAccessObject filesystem
     *
     * @params $src string
     * @params $dest string
     * @return boolean
     */
    public function create($src, $dest);

    /**
     * @params $filepath string
     * @return boolean
     */
    public function delete($filepath);
}