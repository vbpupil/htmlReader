<?php
/**
 * urlValidator.php
 *
 * @author: Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil;


class urlValidator
{
    /**
     * @var bool
     */
    protected $protocol = true;

    /**
     * @var bool
     */
    protected $www = true;

    /**
     * validates a url based on class rules
     *
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public function validate($url)
    {
        $url = parse_url($url);

        $url['full'] = "{$url['scheme']}://{$url['host']}";
        $url['mid'] = str_replace('www.','',$url['host']);

        if ($this->protocol == true) {
            if (isset($url['scheme'])) {
                if (!preg_match('~^https?~', $url['scheme'])) {
                    throw new \Exception('Invalid URL no http protocol specified.');
                }
            }
        }

        if (!isset($url['host']) || preg_match('~^[^.]*$~', $url['mid'])) {
            throw new \Exception('Invalid URL.');
        }

        if ($this->www == true) {
            if (!preg_match('~^www.~', $url['host'])) {
                throw new \Exception('Invalid URL no WWW. specified.');
            }
        }



        return $url;
    }
}