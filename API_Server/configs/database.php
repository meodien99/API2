<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 3/24/14
 * Time: 10:56 AM
 */
$url = "mysql://root:root@localhost?api";
$db = parse_url($url);
if(!isset($db['pass']))
    $db['pass'] = null;

return array(
    'type' => $db['scheme'],
    'user' => $db['user'],
    'pass' => $db['pass'],
    'host' => $db['host'],
    'dbname' => $db['query']
);