<?php
/**
 * setRoot?
 */
class Ext_File_Transfer_Adapter_Local implements Ext_File_Transfer_Adapter_Interface
{
    protected $_rootPath = '/';

    public function isDir($pathname)
    {
        return is_dir($this->_getRealPath($pathname));
    }

    public function mkDir($pathname, $mode = 0777, $recursive = false)
    {
        return mkdir($this->_getRealPath($pathname), $mode, $recursive);
    }

    public function rename($src, $dest)
    {
        return rename($this->_getRealPath($src), $this->_getRealPath($dest));
    }

    public function create($src, $dest)
    {
        $file_path = ($src instanceof Ext_File) ? $src->getFilePath() : $src; // Будет везде дублироваться?
        return $this->rename($this->_getRealPath($file_path), $this->_getRealPath($dest));
    }

    public function setRoot($rootPath)
    {
        $this->_rootPath = '/' . trim($rootPath, '/\\');
    }

    protected function _getRealPath($path)
    {
        $full_path = $path;
        if (0 !== strpos($path, '/')) {
            $full_path = $this->_rootPath . '/' . $full_path;
        }

        return $full_path;
    }
}