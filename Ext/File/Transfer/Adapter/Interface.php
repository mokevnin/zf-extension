<?php

/**
 * Имею ли функции is_readable, is_writable какое то отношение к этому интерфейсу?
 * Думаю стоит покопаться в реализациях на других языках
 */
interface Ext_File_Transfer_Adapter_Interface
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
     * Перемещает только локально или может из локальной в удаленную и наоборот?
     */
    public function rename($src, $dest);

    /**
     * Вопрос такой же как и move
     */
    //public function copy($src, $dest);

    /**
     * В этом методе подразумевается использование move_upload_file и перемещение файла из /tmp
     * на удаленную машину, но тут нужно обговаривать, пока не очень понятно.
     *
     */
    public function create($src, $dest);
}
