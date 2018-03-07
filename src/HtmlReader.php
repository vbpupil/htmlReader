<?php
/**
 * HtmlReader.php
 *
 * @author: Dean Haines
 * @copyright: Dean Haines, 2018, UK
 * @license: GPL V3.0+ See LICENSE.md
 */

namespace vbpupil;


class HtmlReader
{

    /**
     * @var string
     */
    protected $url;

    /**
     * html body
     * @var string
     */
    protected $body;

    /**
     * @var
     */
    protected $domDoc;


    public function __construct($url)
    {
        $this->setUrl($url);
    }

    /**
     * @return mixed
     */
    public function getDomDoc()
    {
        return $this->domDoc;
    }

    /**
     * @param \DOMDocument $domDoc
     */
    public function setDomDoc(\DOMDocument $domDoc)
    {
        $this->domDoc = $domDoc;
        $this->domDoc->loadHTML($this->getBody());
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }


    /**
     * @param $path the img/css/jq file path
     * @return bool
     */
    public function isASecurePath($path)
    {
        if (isset($path)) {
            //literal check for https text
            if (preg_match('~^https~', $path)) {
                return true;
            }

            //check if the resource is located on our site
            if (strpos($path, $this->getUrl()) !== false && preg_match('~^https~', $this->getUrl())) {
                return true;
            }

            if (preg_match('~^\/~', $path)) {
                return $this->isASecurePath($this->url . $path);
            }
        }
    }

    protected function searchDom($element = 'a', $attribute = 'href')
    {
        if (isset($this->body)) {
            $result = array();

            foreach ($this->domDoc->getElementsByTagName($element) AS $node) {
                $result[] = $node->getAttribute($attribute);
            }

            return $result;
        }
    }

    public function search($element = 'a', $attribute = 'href')
    {

        foreach ($this->searchDom($element, $attribute) AS $r) {
            if ((strpos($r, $url['host']) !== false) || preg_match('~^\/~', $r)) {
                if (!in_array($checked, $r)) {
                    $results[$url['path']]['local'][] = (preg_match('~^\/~', $r) ? $url['full'] . $r : $r);
                    $log->info("adding '{$element}' {$r}");
                    array_push($checklist, (preg_match('~^\/~', $r) ? $url['full'] . $r : $r));
                }
            } else {
                if (!empty($r))
                    $results[$url['path']]['foreign'][] = $r;
            }
        }
    }


}