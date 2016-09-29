<?php

namespace Bjorvack\ImageStacker\Helpers;

class FileManager
{
    /**
     * Saves the content to a file.
     *
     * @param $content
     * @param $path
     */
    public static function save($content, $path)
    {
        $fp = fopen($path, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
}
