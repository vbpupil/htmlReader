<?php
/**
 * FileName.php Class
 *
 * @author: Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil;


class Filename
{

    public static function urlToFilename($url, $ext='.txt', $unique=false){
        $f = preg_replace('~(https?|www.|[^a-z.])~', '', $url);

        if($unique == true){
            $f .= '_'.preg_replace('~[^0-9]~', '', microtime(false));
        }

        return $f . $ext;
    }


}