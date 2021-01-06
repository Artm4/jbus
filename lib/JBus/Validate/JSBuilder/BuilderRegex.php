<?php
namespace JBus\Validate\JSBuilder;
use JBus\Widget\Component;
use JBus\Validate\ValidateRegex;

class BuilderRegex extends BuilderBase
{
    protected $widget;
    protected $code;
    function __construct(ValidateRegex $validator)
    {
        parent::__construct($validator);
    }
    
    public function build()
    {
        $functionBody='';
        $regex=$this->validator->getRegexClient();
        $functionBody=sprintf("return null!=%s.%s(/%s/g)",'value','match',$regex);        
        $this->defineValidatorFunction($functionBody);
    }
    /*
     * 
     this.sku.validator = function(value, constraints){
                    var error=''; 
                    var isValid=false;
                    for(i in state['validatorList'])
                    {
                        var isValid=state['validatorList']['isValid'].call(this);
                        if(!isValid)
                        {
                             error+=state['validatorList']['message'];
                        }
                    }
                    if(error.length)
                    {
                        that.sku.set('invalidMessage',length);
                    }
                    return isValid;
            }
            
            that.sku.set('invalidMessage',response.getNextError());
            
             that.sku.validate();
     */
}