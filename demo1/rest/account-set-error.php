<?
header('Content-Type: application/json');
header("HTTP/1.0 500");
echo json_encode(array('error'=>'some error'));
?>