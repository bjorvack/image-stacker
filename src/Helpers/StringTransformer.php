<?php

namespace Bjorvack\ImageStacker\Helpers;

class StringTransformer
{
    /**
     * Transforms the string to a valid slug.
     *
     * @param $string
     *
     * @return string
     */
    public static function slugify($string)
    {
        $string = utf8_encode($string);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
        $string = str_replace(' ', '-', $string);
        $string = trim($string, '-');
        $string = strtolower($string);

        if (empty($string)) {
            return 'n-a';
        }

        return $string;
    }

    /***
     * Removes all whitespaces and newlines.
     *
     * @param $string
     *
     * @return string
     */
    public static function removeWhiteSpace($string)
    {
        return str_replace("\r", '', str_replace("\n", '', str_replace("\t", '', str_replace(' ', '', $string))));
    }
}
