<?php
$itemSet=array();
//$max=time()%4;
$max=4;
for($i=0;$i<$max;$i++)
{    
    $item=array();    
    $item['id']=$i;
    $item['name']='name'.$i;
    $item['label']='label'.$i;    
    $itemSet[]=$item;
}
http://localhost/test/dojo/demo1/rest/filtering-select.php/1
if(preg_match('|filtering-select.php/(\d+)|', $_SERVER['REQUEST_URI'],$matches))
{
    $id=$matches[1];
    $result=$itemSet[$id];
}
else
{
    $result=$itemSet;
}
header('Content-Type: application/json');
echo json_encode($result);