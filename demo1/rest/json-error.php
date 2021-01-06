<?php
$obj=array(
  'body'=>1,
  'errors'=>array(array('error'=>'some error','errno'=>102),
          array('error'=>'some error3','errno'=>103)
  ),
  'messages'=>array('message 1','message2'),
);
header('Content-Type: application/json');
echo json_encode($obj);