<?php

/**
 * Usage:
 *   ...
 *   error_reporting(E_ALL);
 *   if (<is debug mode active>) {
 *       $exceptionizer = new PHP_Exceptionizer();
 *       // and remain $exceptionizer active, e.g. in globals
 *   }
 *   ...
 *   try {
 *       echo $undefinedVariable;
 *   } catch (E_NOTICE $e) {
 *       echo "Notice raised: " . $e->getMessage();
 *   }
 */
class PHP_Exceptionizer 
{
    public function __construct($mask = E_ALL, $ignoreOther = false) 
    {
        $catcher = new PHP_Exceptionizer_Catcher();
        $catcher->mask = $mask;
        $catcher->ignoreOther = $ignoreOther;
        $catcher->prevHdl = set_error_handler(array($catcher, "handler"));
    }

    public function __destruct() 
    {
        restore_error_handler();
    }
}


class PHP_Exceptionizer_Catcher 
{
    public $mask = E_ALL;
    public $ignoreOther = false;
    public $prevHdl = null;

    public function handler($errno, $errstr, $errfile, $errline) 
    {
        if (!($errno & error_reporting())) return false;
        if (!($errno & $this->mask)) {
            if (!$this->ignoreOther) {
                if ($this->prevHdl) {
                    $args = func_get_args();
                    call_user_func_array($this->prevHdl, $args);
                } else {
                    return false;
                }
            }
            return true;
        }
        $types = array(
            "E_ERROR", "E_WARNING", "E_PARSE", "E_NOTICE", "E_CORE_ERROR",
            "E_CORE_WARNING", "E_COMPILE_ERROR", "E_COMPILE_WARNING",
            "E_USER_ERROR", "E_USER_WARNING", "E_USER_NOTICE", "E_STRICT",
            "E_RECOVERABLE_ERROR"
        );
        $className = "E_EXCEPTION";
        foreach ($types as $t) {
            $e = constant($t);
            if ($errno & $e) {
                $className = $t;
                break;
            }
        }
        throw new $className($errno, $errstr, $errfile, $errline);
    }
}


abstract class PHP_Exceptionizer_Exception extends Exception 
{
    public function __construct($no = 0, $str = null, $file = null, $line = 0) 
    {
        parent::__construct($str, $no);
        $this->file = $file;
        $this->line = $line;
    }
}

/**
 * The logic is: if we catch E_WARNING, we also need NOT to pass out
 * E_NOTICE, but we must let E_ERROR to be passed thru.
 */
class E_CORE_ERROR extends PHP_Exceptionizer_Exception {}
    class E_CORE_WARNING extends E_CORE_ERROR {}
    class E_COMPILE_ERROR extends E_CORE_ERROR {}
        class E_COMPILE_WARNING extends E_COMPILE_ERROR {}
    class E_ERROR extends E_CORE_ERROR {} 
        class E_RECOVERABLE_ERROR extends E_ERROR {} 
            class E_PARSE extends E_RECOVERABLE_ERROR {}
                class E_WARNING extends E_PARSE {}
                    class E_NOTICE extends E_WARNING {}
                        class E_STRICT extends E_NOTICE {}
    class E_USER_ERROR extends E_ERROR {}
        class E_USER_WARNING extends E_USER_ERROR {}
            class E_USER_NOTICE extends E_USER_WARNING {}
