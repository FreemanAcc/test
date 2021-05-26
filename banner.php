<?php
require_once 'Logger.php';

header("Content-Type: image/jpeg");

if (isset($_SERVER['HTTP_REFERER'])){

    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT']? $_SERVER['HTTP_USER_AGENT'] : '';


    $uri = parse_url($_SERVER['HTTP_REFERER'],PHP_URL_PATH);
    $filename = str_replace('/','\\',explode('.',$uri)[0]);
    Logger::log($ip_address, $user_agent, $uri);
    echo file_exists(__DIR__.$filename.'.jpg')?file_get_contents(__DIR__.$filename.'.jpg'):file_get_contents(__DIR__.'\index1.jpg');

}



?>