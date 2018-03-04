<?php
/**
 * urlValidator.php
 *
 * @author: Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil\src;


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
    protected function validateUrl($url)
    {
        $url = parse_url($url);

        if ($this->protocol == true) {
            if (isset($url['scheme'])) {
                if (!preg_match('~^https?~', $url['scheme'])) {
                    throw new \Exception('invalid URL no http or https supplied');
                }
            }
        }

        if (!isset($url['host'])) {
            throw new \Exception('URL not present.');
        } else {

        }
        if ($this->www == true) {
            if (!preg_match('~^www~', $url['host'])) {
                throw new \Exception('invalid URL no www supplied');
            }
        }

        $url['full'] = "{$url['scheme']}://{$url['host']}";
        return $url;

    }
}