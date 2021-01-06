   <div style="overflow:auto;width:100%;height:100%;">
    <div data-dojo-type="dijit/layout/BorderContainer" style="overflow:auto;width:100%;height:100%;">
                <span class="tabContainer tabs" data-dojo-props="region:'top'" data-dojo-type="dijit/layout/TabContainer">
                    <div data-dojo-type="dijit.layout.ContentPane" title="Services" selected="true">
                        adfad fasdf asdf asdf
                        <?php
                            echo $this->button;
                            ?> 
                            <?php 
                            echo $this->productName;
                            ?>
                           
                            <?php
                            echo $this->innerWidget;
                            ?>  
                            <?php 
                            echo $this->gridD;
                            ?>  
                    
                    <p><strong>Please agree to the following terms and conditions:</strong></p>
                   <button onclick="hideDialog();">I Agree</button>
                        <button onclick="alert('You must agree!');">I Don't Agree</button>
                         </div>
                    <div data-dojo-type="dijit.layout.ContentPane" title="Tab2" selected="true">     
                        Tab content 2
                     </div>
                </span>


</div>
</div>