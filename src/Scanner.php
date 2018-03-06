<?php
/**
 * Scanner.php Class
 *
 * @author: Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil;


class Scanner
{

    /**
     * @var HtmlReader
     */
    protected $reader;

    public function __construct(\vbpupil\HtmlReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * define what element you want to find ie a tag
     * also define what attribute you wish to get ie href
     *
     * @param $element
     * @param $attribute
     */
    public function scan($element, $attribute)
    {
        if(isset($locate)){
            //scan through the
        }
    }
}