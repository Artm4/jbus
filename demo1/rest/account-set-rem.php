<?php
session_start();
include 'init.php';
Log::info();
$result=array();
$itemBase=array(
    'id'=>0,
    'name'=>'Asen',
    'desc'=>'desc',
    'date'=>''
);
$max=20;
$method=$_SERVER["REQUEST_METHOD"];
$rest=new Rest();
$param=$rest->getParam();
$itemSet=array();
/*called restStore->remove()*/
if($method=='DELETE')
{
    $_SESSION['del']=1;
    //$result['id']=6;
    //header("HTTP/1.0 204 No Content");
    //header('Status Code: 204');
    
    //$rest->header(19);
    exit;
}
else/*called restStore->add()*/    
if($method=='POST')
{
    
    header("HTTP/1.0 201 Created");
    header('Status Code: 201');
    $item=array();   
    $item['id']=21;
    //$item['id']=4;
    $item['name']='Vasko3';
    $item['desc']='desc3';
    $item['date']='date3';
    $result=$item;
    
}
else/*called restStore->put()*/  
if($method=="PUT")
{    
    $item=array();
    $item['id']=$param;
    $item['name']='Vasko2';
    $item['desc']='desc2';
    $item['date']='date2';
    $result=$item;
}
else
for($i=0;$i<$max;$i++)
{    
    $item=array();
    if(isset($_SESSION['del'])&&$i==4)
    {           
        continue;
    }    
    $item['id']=$i;
    $item['idet']=$i;
    $item['name']=$itemBase['name'].$i;
    $item['desc']=$itemBase['desc'].$i;
    $item['date']=date("d/m/Y H:i:s",strtotime("-{$i} minutes"));
    $itemSet[]=$item;
}
if(count($itemSet))
{
    $result=array_slice($itemSet, $rest->getStart(),$rest->getEnd()-$rest->getStart());
}
$rest->header(count($itemSet));
echo json_encode($result);
?>