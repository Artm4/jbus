<?php
header('Content-Type: application/json');
define('VENDOR_PATH', dirname(__FILE__).'/../../vendor');

$loader=require VENDOR_PATH.'/autoload.php';
require 'uploadFileClass.php';
$uploadFile=new UploadFile($_FILES);
$uploadPath=dirname(__FILE__).'/tests/uploads';

$result=array();
$uploadFile->move($uploadPath);
foreach($uploadFile->getUploadResult() as $uploadResult)
{   
    $file=$uploadResult->getFile();
    $status=$uploadResult->isSuccessfull();
    $fileArr=array();
    $fileArr['path'] = $file->getPathname();
    $fileArr['name'] = $file->getBasename();
    $fileArr['width'] = $file->getWidth();
    $fileArr['height'] = $file->getHeight();
    $fileArr['type'] = $file->getMimetype();
    $fileArr['size'] = $file->getSize();
    $fileArr['result'] = $status;
    $fileArr['originalName'] = $file->getOriginalName();
    $fileArr['files'] = $_FILES;
    $result[]=$fileArr;    
}
echo json_encode($result);