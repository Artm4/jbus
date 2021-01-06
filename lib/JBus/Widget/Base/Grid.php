<?php
namespace WidgetBase;

use JBus\JSBuilder\GridBuilder;
use JBus\JSBuilder\FunctionList;
use JBus\Widget\Component;
use JBus\Widget\State;
use WidgetBase\Grid\GridColumn;
use WidgetBase\Grid\EventColumn;

class Grid extends Component
{
    protected $jsFunctionName=FunctionList::GRID;
    protected $templatePath='grid-template.php';    
    /*
     *  
            this.grid = new Grid({               
                collection: this.storeAssemblyFiltered,
                className: 'dgrid-autoheight',
                columns: {       
                    itemLinkId: 'Id',
                    nameAssembly: 'Component Name',                    
                    qty: 'Qty',                    
                },
                rowsPerPage: 15,
                noDataMessage: dojoConfig.app.constant.noData,
            });
     */
    public function onCreate()
    {
        parent::onCreate();
        $this->state->set('jbQueryParams','');
        $this->state->set('rowObject','');
        $this->state->set('rowsPerPage',10,true);
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new GridBuilder($this);
        }
        return $this->builder;
    }
    
    public function setData($data,$total=0)
    {   
        if(0==$total)
        {
            $total=count($data);
        }
        $this->sliceData($data);
        $this->castType($data);
        $this->state->set('data',$data,true);                
        $this->state->set('total',$total,true);
    }
    
    
    
    public function setColumns($columns)
    {        
        foreach($columns as $key=>$columnDef)
        {
                if(is_string($columnDef))
                {
                    $columnList[]=new GridColumn($key,$columnDef);
                }
                else 
                if(is_array($columnDef))
                {
                    
                    $column=new GridColumn($key,$columnDef[0]);                    
                    $column->setWidgetList($columnDef[1]);
                    $this->transformWidgetEvent($column);
                    $columnList[]=$column;
                }
        }
        
        $this->state->set('columns',$columnList,true);
    }
    
    public function remove($id)
    {
        $this->state->set('removeId',$id,true);
    }
    
    public function put($item)
    {
        $this->state->set('putItem',$item,true);
    }
    
    public function refresh()
    {
        $this->state->set('refresh',true,true);
    }
    
    public function setPrimaryName($name)
    {
        $this->state->set('primaryName',$name,true);
    }
    
    public function getRowObject()
    {
        return  $this->state->get('rowObject');
    }
    
    public function getQueryParamList()
    {
        $paramList=array();
        if(!strlen($this->state->jbQueryParams))
        {
            return $paramList;            
        }
        $paramListPair=explode(';', $this->state->jbQueryParams);
        foreach($paramListPair as $pair)
        {
            $pairList=explode('=', $pair);
            $key=$pairList[0];
            $value=isset($pairList[0])?$pairList[0]:'';
            $paramList[$key]=$value;
        }
        return $paramList; 
    }
    
    public function createFilter()
    {
        return new \Lib\Selector\Filter\Dojo($this->getQueryParamList());    
    }
    
    public function registerOnQuery($obj,$methodCallback)
    {
        $this->getEventWidget()->addEvent(GridBuilder::EVENT_QUERY, $obj,$methodCallback);
    }
    
    public function setRowsPerPage($rowsPerPage)
    {
        $this->state->set('rowsPerPage',$rowsPerPage,true);
    }
    protected function getRowsPerPage()
    {
        return $this->state->get('rowsPerPage');
    }
    
    protected function transformWidgetEvent(GridColumn $column)
    {
        if(!$column->hasWidget())
        {
            return false;
        }
        $widgetList=$column->getWidgetList();
        foreach($widgetList as $widget)
        {
            $eventWidget=$widget->getEventWidget();
            $eventColumn=new EventColumn($widget, $this);
            $widget->setEventWidget($eventColumn);
        }
    }
    
    protected function sliceData(&$data)
    {
        $size=count($data);
        if($size>$this->getRowsPerPage())
        {
            $filter=$this->createFilter();
            $data=array_slice($data,$filter->getOffset(),$filter->getLimit());
        }
        return $data;
    }
    
    protected function castType(&$data)
    {
        if(!count($data))
        {
            return $data;
        }
        $numericFieldPos=array();
        foreach($data[0] as $k=>$v)
        {
            if(is_numeric($v))
            {
                $numericFieldPos[]=$k;
            }
        }
        if(!count($numericFieldPos))
        {
            return $data;
        }
        foreach($data as $i=>$row)
        {
            foreach($numericFieldPos as $k)
            {
                if(is_numeric($row[$k]))
                {
                    $data[$i][$k]=$row[$k]+0;
                }
            }
        }
        return $data;
    }
}