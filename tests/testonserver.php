<?php declare(strict_types=1);
error_reporting(E_ALL);
require_once("../vendor/autoload.php");
// ini_set("display_errors", 1);
use Saeedmou\HttpResponse\HttpResponse;

$httpResponse=new HttpResponse();
$httpResponse->sendHeaders();
$httpResponse->setResponseParameters(true,"Test",["root",array("data1"=>null,"data2"=>"test","data3"=>55)],false);
$array=array(
    "status"=>true,
    "message"=>"Test",
    "data"=>["root",array("data1"=>null,"data2"=>"test","data3"=>66)]
);
$httpResponse->setResponseArray($array);

$httpResponse->responseJson(true);
// var_dump($httpResponse);