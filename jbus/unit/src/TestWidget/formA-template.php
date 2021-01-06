<div> 
    <table style="border-collapse: collapse; width: 60%;">
        <tr>
            <td style="width:45%;">
                <?echo $this->search?>
                <?php echo $this->uploader?>
            </td>
            <td>
                <?php echo $this->label?>
                <?php echo $this->openDialog?>
            </td>
        </tr>
    </table>
        <table border="0" style="border-collapse: collapse; width: 60%; height: 49%;">
            <tbody>
                <tr>
                    <th>
                        Add Variation Product</th>
                    <th>
                        </th>
                </tr>                
                <tr>
                    <td>
                        Name</td>
                    <td>
                        <?echo $this->productName?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Company</td>
                    <td>
                        <?echo $this->company?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Image</td>
                    <td>
                        <?echo $this->image?>
                    </td>
                </tr>
                <tr>
                    <td>
                        SKU</td>
                    <td>
                        <?echo $this->sku?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Generic Product</td>
                    <td>
                        <!-- select-->                        
                    </td>
                </tr>
                <tr>
                <td>
                   Description
                </td>
               <td>
                   <?echo $this->description?>
               </td>  
                </tr>
                <tr>
                    <td>
                        </td>
                    <td id="saveItem">
                        <!-- save-->
                    </td>
                </tr>
                <tr>
                    <td>
                        </td>
                    <td id="saveItem">
                        
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
              
        </div>        
        <div data-dojo-type="dijit/layout/TabContainer">
            <div data-dojo-type="dijit.layout.ContentPane" title="Grid" selected="true">
                <div>
                <?echo $this->grid;?>
                </div> 
            </div>        
            <div data-dojo-type="dijit.layout.ContentPane" title="Gridb">
                <div>
                <?echo $this->gridB;?>
                </div> 
                <div>
                  sdfsdfasdf
                </div>
            </div>
        </div>
        <?php echo $this->dialog?>
</div>