<?php

define('VENDOR_PATH', dirname(__FILE__).'/../../vendor');

$loader=require VENDOR_PATH.'/autoload.php';

//
// 	If the data passed has 'uploadedfiles' (plural), then it's an HTML5 multi file input.
//
$cnt = 0;
//trace($_FILES, true);
//print_r($_FILES);
$_post = $postdata = $_POST;
$htmldata = array();
$len = count($_FILES['uploadedfiles']['name']);
//
// Ugh. The array passed from HTML to PHP is fugly.
//

$storage = new \Upload\Storage\FileSystem('tests/uploads/');
for($i=0;$i<$len;$i++){   
    $file = new \Upload\File($_FILES['uploadedfiles']['name'][$i], $storage);
    
    try {
        // Success!
        
        $result=$file->upload();  
        $sizeList = $file->getDimensions();
        $width=$sizeList['width'];
        $height=$sizeList['height'];
      
        $_post['file'] = $file->getFileName();
        $_post['name'] = $file->getBasename();
        $_post['width'] = $width;
        $_post['height'] = $height;
        $_post['type'] = $file->getMimetype();
        $_post['size'] = $file->getSize();
        
        //if some error but file uploaded
        //$_post['filesInError'] = $name;
        
        $htmldata[$cnt] = $_post;
    } catch (\Exception $e) {
        // Fail!        
        $errors = $file->getErrors();
        $errorMessage=implode("\n", $errors);
        $htmldata[$cnt] = array("ERROR" => $errorMessage);
    }
    $cnt++;
}

$data = $json->encode($htmldata);
trace($data);
print $data;
return $data;



