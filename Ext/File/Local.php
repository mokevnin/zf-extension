<?php
/**
 * setRoot?
 */
class Ext_File_Local implements Ext_File_Interface
{
    protected $_currentDir;

    public function isDir($pathname)
    {
        return is_dir($this->_getFullPath($pathname));
    }

    public function mkDir($pathname, $mode = 0777, $recursive = false)
    {
        $full_path = $this->_getFullPath($pathname);
        $result = @mkdir($full_path, $mode, $recursive);
        if (!$result) {
            throw new Ext_File_Exception("'$full_path' does not writable");
        }
        return true;
    }

    public function isWritable($pathname)
    {
        return is_writable($this->_getFullPath($pathname));
    }

    public function rename($src, $dest)
    {
        return rename($this->_getFullPath($src), $this->_getFullPath($dest));
    }

    public function copy($src, $dest)
    {
        return copy($this->_getFullPath($src), $this->_getFullPath($dest));
    }

    public function create($src, $dest)
    {
        return $this->rename($this->_getFullPath($src), $this->_getFullPath($dest));
    }

    public function setCurrentDir($pathname)
    {
        $this->_currentDir = rtrim($pathname, '/\\') . '/';
    }

    protected function _getFullPath($path)
    {
        $full_path = $path;
        if (0 !== strpos($path, '/')) {
            $full_path = $this->_currentDir . $path;
        }

        return $full_path;
    }
}