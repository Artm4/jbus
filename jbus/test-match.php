<?php
$subject='<button widget  ="button">
</button>

<div widget="grid">
</div>

<div widget="autocompleteConfigs">
</div>

<div widget="productExtConfig">
</div>';
/*
$pattern='|widget[ \t]*=[ \t]*[\'"](\w+)[\'"]|';
preg_match_all($pattern, $subject,$matches);
print_r($matches);

$arr=array('a'=>'v',2,3,'k'=>array(1,2,4));
function cloneArray($arr)
{
    $newArr=array();
    foreach ($arr as $k=>$v)
    {
        if(is_array($v))
        {
            $newArr[$k]=cloneArray($v);
        }
        else
        {
            $newArr[$k]=$v;
        }
    }
    return $newArr;
}
$barr=cloneArray($arr);
$arr['a']=1;
print_r($barr);

*/
$type='WidgetBase_Button-TestWidget_FormA-compositeWidget>button';
preg_match("/(\w*)\-(\w*)\-(.*)/", $type,$matches);
print_r($matches);

//echo "adsfadf\\n\123";
//var_dump(preg_replace("/\n/", "\\0", "\n some text \n other \n sfsd \n  \n"));
//var_dump(json_encode('adsaf \n adfa'));
//var_dump(str_replace("\n", "\\n", "\n some text \n other \n sfsd \n  \n"));