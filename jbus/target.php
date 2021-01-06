<?php
namespace JBus;
use JBus\Widget\StateCache;

header('Content-Type: application/json');
include 'boot.php';
$request=Request::create();
//print_r($request->getTree());
$response=new Response();
$params=$request->restGetAllParams();
//print_r($params);
$request->handleEvent();
//print_r($response->createAjaxResponse());
echo json_encode($response->createAjaxResponse());