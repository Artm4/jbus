<?php
header('Content-Type: application/json');
//{"body":[],"errors":[],"messages":[],"requireList":[]
$response=array(
    'body'=>array(),
    'errors'=>array(),
    'message'=>array(),
    'requireList'=>array(),
    'code'=>'console.log(1231)',
);
echo json_encode($response);