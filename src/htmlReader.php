<?php
/**
 * htmlReader.php
 *
 * @author: Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil\src;


class htmlReader
{

    protected $url;


    public function __construct($url)
    {
        if($this->validateUrl($url)){
            $this->url = $url;
        }
    }





}