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
    protected $headers;

    /**
     * @var
     */
    protected $webClient;

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

    /**
     * @var array
     */
    protected $checked = array();

    /**
     * @var array
     */
    protected $checklist = array();

    /**
     * @var array
     */
    protected $checkedImages = array();

    /**
     * @var array
     */
    protected $results = array();


    public function __construct($url, \GuzzleHttp\Client $client)
    {
        $this->setUrl($url);
        $this->webClient = $client;

        return $this;
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

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl($type = 'full')
    {
        return $this->url[$type];
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
     */
    public function setBody()
    {
        $this->body = $this->headers->getBody();
        return $this;
    }

    /**
     * returns the page headers
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->headers->getStatusCode();
    }



    public function connect($command='GET')
    {
        $this->headers = $this->webClient->request('GET', $this->url['full']);
        return $this;
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
            if (strpos($path, $this->getUrl('full')) !== false && preg_match('~^https~', $this->getUrl('full'))) {
                return true;
            }

            if (preg_match('~^\/~', $path)) {
                return $this->isASecurePath($this->url . $path);
            }
        }
    }

    /**
     * @param string $element
     * @param string $attribute
     * @return array
     */
    protected function searchDom($element = 'a', $attribute = 'href')
    {
        if (isset($this->body)) {
            $result = array();

            foreach ($this->domDoc->getElementsByTagName($element) AS $node) {
                $result[] = $node->getAttribute($attribute);
            }

            return array_unique($result);
        }
    }

    public function search($element = 'a', $attribute = 'href')
    {
        foreach ($this->searchDom($element, $attribute) AS $r) {
            if (((strpos($r, $this->getUrl('host')) !== false) || preg_match('~^(?=^\/)(?!.*www\.).*~', $r))) {
                if (!in_array($this->checked, $r)) {
                    $this->results[$this->getUrl('path')]['local'][] = (preg_match('~^\/~', $r) ? $this->getUrl('full') . $r : $r);
                    array_push($this->checklist, (preg_match('~^\/~', $r) ? $this->getUrl('full') . $r : $r));
                }
            } else {
                if (!empty($r))
                    $this->results[$this->getUrl('path')]['foreign'][] = $r;
            }
        }

        return $this->results;
    }


}