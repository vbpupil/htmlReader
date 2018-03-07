<?php

include 'vendor/autoload.php';

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use vbpupil\Filename;
use vbpupil\HtmlReader;
use vbpupil\UrlValidator;

$logPath = __DIR__ . '/logs/';

try {
    $url = (new UrlValidator)->validate('https://www.ukstoves.co.uk/');
    $HtmlReader = new HtmlReader($url);
    $client = new Client();
    $res = $client->request('GET', $url['full']);

    if ($res->getStatusCode() == 200) {
        $HtmlReader->setBody($res->getBody());
        $HtmlReader->setDomDoc(new DOMDocument());
        var_dump($HtmlReader->search('img', 'src'));
    }

} catch (Exception $e) {

}

//$checklist = [$url['host']];
//$checked = [];
//$checkedImages = [];
//
//while (!empty($checklist)) {
////    $HtmlReader = new HtmlReader($url);
//
//    $client = new Client();
//    $res = $client->request('GET', $checklist[0]);
//
//    array_push($checked, $checklist[0]);
//
//    if ($res->getStatusCode() == 200) {
//        $results = [];
//
//        $HtmlReader->setBody($res->getBody());
//        $HtmlReader->setDomDoc(new DOMDocument());
//        var_dump($HtmlReader->search('img', 'src'));
//
//        foreach ($results as $k => $v) {
//            foreach ($v AS $kk => $vv) {
//                $results[$k][$kk] = array_unique($results[$k][$kk]);
//            }
//        }
//    }else{
//        //log a 404
//    }
//
////    array_shift($checklist);
//}
//
