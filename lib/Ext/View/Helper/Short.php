<?php

class Ext_View_Helper_Short extends Zend_View_Helper_Url
{
    public function short($text, $length = 50, $fullWords = false, $end = '')
    {
        if (!is_string($text) || mb_strlen($text) == 0) return null;

        $text = trim(strip_tags($text));

    	if (mb_strlen($text) <= $length) {
            return $text;
    	}

        if ($fullWords) {
            $tmp_text = mb_substr($text, 0, $length);
            $lastpos = mb_strrpos($tmp_text, ' ');
            return mb_substr($text, 0, $lastpos);
        }

        return mb_substr($text, 0, $length) . $end;
    }
}