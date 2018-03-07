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
//    $log = new Logger('html Reader');
//    $filename = Filename::urlToFilename($url['full'], '.log', true);
//    $log->pushHandler(new StreamHandler($logPath . $filename, Logger::INFO));
} catch (Exception $e) {
//    $log->info($e->getMessage());
}


$checklist = [$url['host']];
$checked = [];
$checkedImages = [];

//$log->info('Starting');

while (!empty($checklist)) {
    $HtmlReader = new HtmlReader($url['full']);

//    $log->info("performing GET on {$checklist[0]}");

    $client = new Client();
    $res = $client->request('GET', $checklist[0]);

    array_push($checked, $checklist[0]);

    if ($res->getStatusCode() == 200) {
        $results = [];

        $HtmlReader->setBody($res->getBody());
        $HtmlReader->setDomDoc(new DOMDocument());
 
//         $dom->loadHTML($HtmlReader->getBody());
        $dom = $HtmlReader->getDomDoc();





        /*getting images*/
/*
                foreach ($dom->getElementsByTagName('img') AS $node) {
                    $img = $node->getAttribute('src');
                    $secure = ($HtmlReader->isASecurePath($img) ? 'SECURE' : 'INSECURE');

                    if ((strpos($img, $url['host']) !== false) || preg_match('~^\/~', $img)) {
                        if (!in_array($checkedImages, $img)) {
                            $results[$url['path']]['images']['local'][] = (preg_match('~^\/~', $img) ? $url['full'] . $img : $img);
                        }
                    } else {
                        if (!empty($img)) {
                            //foreign image located.
                            $results[$url['path']]['images']['foreign'][] = $img;
                        }
                    }

                    $log->info("local {$secure} image located {$img}");
                }
*/


        foreach ($results as $k => $v) {
            foreach ($v AS $kk => $vv) {
                $results[$k][$kk] = array_unique($results[$k][$kk]);
            }
        }
    }else{
        //log a 404
    }

    array_shift($checklist);
}

//var_dump($results);
