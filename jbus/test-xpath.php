<?php
$subject='
<div>
    <button data-widget  ="button">
    </button>

    <div data-widget="grid">
    </div>
    
    <div data-widget="autocompleteConfigs">
    </div>

    <div data-widget="productExtConfig">
        
    </div>
        
    <div data-widget="inputName" id="123-inputName">
    </div>
</div>';

$xml = simplexml_load_string($subject);
$result = $xml->xpath("//*[@data-widget='productExtConfig']");
$xmlTarget=$result[0];
print_r($xmlTarget);
print_r($xml);

$toAdd="<div>
    <button data-widget  ='button'>
    </button>
    <div data-widget='productExtConfig'>
    </div>
        
    <div data-widget='inputName' id='223-inputName'>
    </div>
</div>
        
";


$xmlToAdd=new SimpleXMLElement($toAdd);

$toDom = dom_import_simplexml($xmlTarget);
$fromDom = dom_import_simplexml($xmlToAdd);
$toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));

echo $xml->asXML();
