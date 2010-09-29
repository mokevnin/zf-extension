<?php

class Ext_Filter_Tidy implements Zend_Filter_Interface
{
    public function filter($value)
    {
        if (!extension_loaded('tidy')) {
            trigger_error('Tidy does not installed', E_NOTICE);
            return $value;
        }

        $tidy = new Tidy(); // Создаем объект Tidy
        $tidy_config = array( // А в этом массиве — настройки фильтра
            'show-body-only' => true,
            'drop-empty-paras' => true, // Убираем пустые теги p
            'drop-font-tags' => true, // Убираем теги font
            'drop-proprietary-attributes' => true, // Убираем все специфические микрософтовские атрибуты (например, от Ворда)
            'enclose-block-text' => true, // Все блоки текста заключаем в p
            'enclose-text' => true, // Весь свободный текст (который просто в body, без других тегов) тоже заключаем в p
            'hide-comments' => true, // Комментарии в коде не трогаем
            'hide-endtags' => true, // Убираем необязательные закрывающие теги
            'indent' => true, // Форматируем html, аккуратно расставляя отступы
            'logical-emphasis' => true, // Заменяем теги i и b на em и strong соответственно
            'lower-literals' => true, // Все html-атрибуты приводим к нижнему регистру
            'markup' => true, // Исправляем ошибки разметки
            'output-xhtml' => true, // Выдача в xhtml
            //'quote-ampersand' => true, // Заменяем символы & на &amp;
            //'quote-marks' => true, // Заменяем символы кавычек в тексте на соответствующие html-коды
            'quote-nbsp' => true, // Неразрывные пробелы выводим спецтегом &nbsp; вместо кода символа
            //'show-warnings' => true, // Выводить сообщения о проблемах обработки
            'wrap' => 0, // Убираем расстановк переноса
        );
        $tidy->parseString($value, $tidy_config, 'utf8');
        $tidy->cleanRepair(); // Запускаем обработку

        return tidy_get_output($tidy); // Получаем результаты обработки
    }
}