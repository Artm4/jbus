<?php
include 'init.php';
Log::info();
$result=array();
$item=array(
     'id'=>1,
    'First_Name'=>3,
    'First_Name'=>'Asen',
);
$result[]=$item;
echo json_encode($result);
?>