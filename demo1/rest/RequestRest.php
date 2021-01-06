<?php
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