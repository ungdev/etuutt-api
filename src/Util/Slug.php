<?php

namespace App\Util;

/**
 * This class has a `slugify` static method that takes a text as paremeter and returns the slug of this text.
 *
 * @see https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
 */
class Slug
{
    /**
     * @param string $text the text to convert into string
     *
     * @return string the slug of the text
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\pL\d]+#u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('#[^\-\w]+#', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('#-+#', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
