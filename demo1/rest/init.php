<?php
class Log
{
    public static function info($name='')
    {
        $file=basename($_SERVER['SCRIPT_NAME']);
        $fileNoExt=substr($file, 0,strpos($file, '.'));
        if(false!==$fileNoExt&&strlen($fileNoExt))
        {
            $file=$fileNoExt;
        }
        $nameClear=strlen($name)?$name:$file;
        $log="\n----------------\n";
        $log.=sprintf("%s:%s\n",'date',date('Y-m-d H:i:s'));
        $log.=sprintf("%s:%s\n",'params',print_r($_REQUEST,true));
        $log.=sprintf("%s:%s\n",'request uri',print_r($_SERVER['REQUEST_URI'],true));
        $log.=sprintf("%s:%s\n",'method',print_r($_SERVER['REQUEST_METHOD'],true));
        $log.=sprintf("%s:%s\n",'body',file_get_contents('php://input'));
        $filename=dirname(__FILE__).DIRECTORY_SEPARATOR.$nameClear.'-log.txt';
        $fp=fopen($filename, 'a+');            
        fputs($fp, $log);        
        fclose($fp);
        chmod($filename, 0777);
    }
}

class Rest
{
    private $limit='';
    private $start=0;
    private $end=0;
    private $param='';

     function __construct()
     {
         foreach($_GET as $param => $value){
             if(strpos($param, 'limit') === 0){
                 $this->limit = $param;
                 break;
             }
         }
         if($this->limit){
             preg_match('/(\d+),*(\d+)*/', $this->limit, $matches);
             if(count($matches) > 2){
                 $this->start = $matches[2];
                 $this->end = $matches[1] + $this->start;
             }else{
                 $this->end = $matches[1];
             }
         }
         
         $param=str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
         $this->param=trim($param,'/');
     }
    
     public function header($total)
     {

         header('Content-Type: application/json');       
         $id_prefix = '';
         if(isset($_GET['parent']) && is_numeric($_GET['parent'])){
             $id_prefix = ($_GET['parent'] + 1) * 1000;
         }
         usleep(rand(0,500000));
         $this->start = 0;
         $this->end = 0;
         $this->limit = '';
         $debug = '';
         foreach($_GET as $param => $value){
             if(strpos($param, 'limit') === 0){
                 $this->limit = $param;
                 break;
             }
         }
         if($this->limit){
             preg_match('/(\d+),*(\d+)*/', $this->limit, $matches);
             if(count($matches) > 2){
                 $this->start = $matches[2];
                 $this->end = $matches[1] + $this->start;
             }else{
                 $this->end = $matches[1];
             }
         }else{
             $range = '';
             if(isset($_SERVER['HTTP_RANGE'])){
                 $range = $_SERVER['HTTP_RANGE'];
             }elseif(isset($_SERVER['HTTP_X_RANGE'])){
                 $range = $_SERVER['HTTP_X_RANGE'];
             }
             if($range){
                 preg_match('/(\d+)-(\d+)/', $range, $matches);
                 $this->start = $matches[1];
                 $this->end = $matches[2] + 1;
             }
         }
         if($this->end){
             if($this->end > $total){
                 $this->end = $total;
             }
         }else{
             $this->start = 0;
             $this->end = 1;
         }
         header('Content-Range: ' . 'items '.$this->start.'-'.($this->end-1).'/'.$total);
     }
     
     public function getParam()
     {        
        return $this->param;
     }
     
     public function getStart()
     {
         return $this->start;
     }
     
     public function getEnd()
     {
         return $this->end;
     }
}