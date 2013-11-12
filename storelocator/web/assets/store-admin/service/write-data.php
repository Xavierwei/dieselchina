<?php

function writeData($file){
    $json = json_encode($_GET);
    $jsonp = isset($_GET['callback']) ? "{$_GET['callback']}($json)" : $json;
    
    if (file_exists($file) && is_writable($file)) {
        $fp = fopen($file, 'w+');
        fwrite($fp, $json);
        fclose($fp);
        
        print_r($jsonp);
    }
}