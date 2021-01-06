<?php
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
    header("HTTP/1.0 204 No Content");
    header('Status Code: 204');
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
{
    $parent=isset($_GET['parent'])&&is_numeric($_GET['parent'])?$_GET['parent']:NULL;
    $firstName='First_Name';
    $lastName='Last_Name';
    if(!$parent)
    {
        
        $itemSet=array(
            array('id'=>1,$firstName=>'some1',$lastName=>'some2'),
            array('id'=>2,$firstName=>'some2',$lastName=>'some2'),       
        );
    }
    else
    {
        $itemSet=array(        
        array('id'=>3*$parent,$firstName=>'some11',$lastName=>'some2','parent'=>$parent, 'hasChildren'=>false),
        array('id'=>4*$parent,$firstName=>'some12',$lastName=>'some2','parent'=>$parent, 'hasChildren'=>false),       
    );
    }
   
}
if(count($itemSet))
{
    $result=array_slice($itemSet, $rest->getStart(),$rest->getEnd()-$rest->getStart());
}
$rest->header(count($itemSet));
echo json_encode($itemSet);
?>