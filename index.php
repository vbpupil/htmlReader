<?php

include 'vendor/autoload.php';

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use vbpupil\src\urlValidator;

$log = new Logger('logger');
$log->pushHandler(new StreamHandler('logs/ukstoves.log', Logger::INFO));
$log->info('Starting');

$url = new urlValidator('http://www.ukstoves.co.uk/');
$urlFull = "{$url['scheme']}://{$url['host']}";

var_dump($url); die;

$checklist = [$url['host']];
$checked = [];

while (!empty($checklist)) {
    $log->info("performing GET on {$checklist[0]}");

    $client = new Client();
    $res = $client->request('GET', $checklist[0]);
    array_push($checked,$checklist[0]);

    if ($res->getStatusCode() == 200) {
        $results = [];

        $body = $res->getBody();

        $dom = new DOMDocument();
        $dom->loadHTML($body);
        foreach ($dom->getElementsByTagName('a') AS $node) {
            $a = $node->getAttribute('href');

            if ((strpos($a, $url['host']) !== false) || preg_match('~^\/~', $a)) {
                if(!in_array($checked, $a)) {
                    $results[$url['path']]['local'][] = (preg_match('~^\/~', $a) ? $urlFull . $a : $a);
                    $log->info("adding 'A' {$a}");
                    array_push($checklist, (preg_match('~^\/~', $a) ? $urlFull . $a : $a));
                }
            } else {
                if (!empty($a))
                    $results[$url['path']]['foreign'][] = $a;
            }
        }

        foreach ($results as $k => $v) {
            foreach ($v AS $kk => $vv) {
                $results[$k][$kk] = array_unique($results[$k][$kk]);
            }
        }
    }

    array_shift($checklist);
}

var_dump($results);
