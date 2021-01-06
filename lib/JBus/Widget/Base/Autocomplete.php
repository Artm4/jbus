<?php
namespace WidgetBase;

use JBus\JSBuilder\FunctionList;
use JBus\JSBuilder\AutocompleteBuilder;

class Autocomplete extends FormElement
{   
    protected $jsFunctionName=FunctionList::FILTERING_SELECT;
    protected $templatePath='autocomplete-template.php';
    
    const SEARCH_ATTR='name';
    const LABEL_ATTR='label';
    const IDENTIFIER='id';
    
    public function onCreate()
    {
        parent::onCreate();
        $this->setSearchAttr(self::SEARCH_ATTR);
        $this->setLabelAttr(self::LABEL_ATTR);        
        $this->state->set('identifier',self::IDENTIFIER,true);
        $this->state->set('searchText','');
    }
    
    protected function setSearchAttr($searchAttr)
    {
        $this->state->set('searchAttr',$searchAttr,true);
        return $this;
    }
    
    protected function setLabelAttr($labelAttr)
    {
        $this->state->set('labelAttr',$labelAttr,true);
        return $this;
    }
    public function setDisabled()
    {
    	$this->state->disabled='true';
    }
    public function setEnabled()
    {
    	$this->state->disabled='false';
    }
    public function getSearchText()
    {
        return $this->state->get('searchText');
    }
    
    public function setData($data)
    {
        $items=array();
        foreach ($data as $key => $value) {
            $items[] = array(self::IDENTIFIER => $key, $this->state->searchAttr => $value, $this->state->labelAttr => $value);
        }
        $this->state->set('data',$items,true);   
        return $this;
    }
    
    /**
     * Set selected value. Should be used when autocomplete is filled by search event.
     * @param mixed $key
     * @param mixed $label
     */
    public function setItem($key,$value)
    {   
        $item=array(self::IDENTIFIER => $key, $this->state->searchAttr => $value, $this->state->labelAttr => $value);
        $this->state->set('item',$item,true);
        return $this;
    }
    
    //`${0}` will be substituted for the user text.
		//		`*` is used for wildcards.
		//		`${0}*` means "starts with", `*${0}*` means "contains", `${0}` means "is"
    public function setQueryExpr($queryExpr)
    {
        $this->state->set('queryExpr',$queryExpr,true);
        return $this;
    }
    
    public function getBuilder()
    {
        if(NULL==$this->builder)
        {
            $this->builder=new AutocompleteBuilder($this);
        }
        return $this->builder;
    }
    
    public function registerOnSearch($obj,$methodCallback)
    {
        $this->getEventWidget()->addEvent(AutocompleteBuilder::EVENT_SEARCH, $obj,$methodCallback);
    }   
}