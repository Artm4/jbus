<div data-dojo-type="dijit/layout/BorderContainer">

                <span class="tabContainer tabs" data-dojo-props="region:'top'" data-dojo-type="dijit/layout/TabContainer" tabStrip="false" persist="false" controllerWidget="dijit.layout.TabController">
                    <div data-dojo-type="dijit.layout.ContentPane" title="Services" selected="true">
                        <div>
      <?php
    echo $this->button;
    ?> 
    <?php 
    echo $this->productName;
    ?>
    </div>
    <?php
    echo $this->innerWidget;
    ?>  
    <?php 
    echo $this->gridD;
    ?>  
    <div >
     asd fasdfasd
    </div>
                    </div>
                    
                    <div data-dojo-type="dijit.layout.ContentPane" title="Add Service" >
                        
                    </div>  

                    <div data-dojo-type="dijit.layout.ContentPane" title="Product List" >
                       
                    </div>
                    
                    <div data-dojo-type="dijit.layout.ContentPane" title="Add Product">
                       
                    </div>
                </span>


</div>