<?php

function genID($email, $event, $pcount=1){
    $hash = strtoupper(substr(sha1($email.$event.$pcount), 0, 6));
    if($pcount > 1){
        return "VS-".$event."-".$pcount."-".$hash;
    } else {
        return "VS-".$event."-".$hash;
    }
}

function genFileKey(){
    $seed = myexplode("abcdefghijklmnopqrstuvwxyz1234567890");
    $uname = array();
    $size = 0;
    while($size != 10){
        $uname[$size] = $seed[mt_rand(0,count($seed)-1)];
        $size++;
    }
    return implode($uname);
}

function genApiKey(){
    $seed = myexplode("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
    $uname = array();
    $size = 0;
    while($size != 18){
        $uname[$size] = $seed[mt_rand(0,count($seed)-1)];
        $size++;
    }
    return implode($uname);
}

function genPassKey(){
    $seed = myexplode("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");
    $uname = array();
    $size = 0;
    while($size != 7){
        $uname[$size] = $seed[mt_rand(0,count($seed)-1)];
        $size++;
    }
    return implode($uname);
}

function myexplode($str){
    $out = array();
    for ($i = 0; $i < strlen($str); $i++) {
        $out[$i] = substr($str, $i, 1);
    }
    return $out;
}

function getMicro(){
    return explode(' ', microtime())[1];
}

function readFileData($path){
    $file = fopen($path,"r") or die();
    $data = fread($file,filesize($path));
    fclose($file);
    return $data;
}

function isFileExist($path){
    if (file_exists($path)) {
        return 1;
    }
    return 0;
}

function toBase64($data){
    return base64_encode($data);
}

function fromBase64($data){
    return base64_decode($data);
}

function urlsafe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return $data;
}

function urlsafe_b64decode($string) {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

function toJson($data){
    return json_encode($data);
}

function fromJson($data){
    return json_decode($data, true);
}

function PlainDie($status = "404 File Not Found"){
    header('Content-type: text/plain');
    die($status);
}

//api url filter
if(strpos($_SERVER['REQUEST_URI'],"utils.php")){
    PlainDie();
}