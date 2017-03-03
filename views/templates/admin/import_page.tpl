{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<style>
    .datagrid table 
    { 
        border-collapse: collapse; 
        text-align: left; 
        width: 100%; 
    } 
    .datagrid 
    { font: normal 12px/150% Arial, Helvetica, sans-serif; 
      background: #fff; 
      overflow: hidden; 
      border: 1px solid #006699; 
      -webkit-border-radius: 3px; 
      -moz-border-radius: 3px; 
      border-radius: 3px; 
    }
    .datagrid table td, .datagrid table th 
    { 
        padding: 3px 10px; 
    }
    .datagrid table thead th 
    { 
        background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );
        background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');
        background-color:#006699; 
        color:#FFFFFF; 
        font-size: 15px; 
        font-weight: bold; 
        border-left: 1px solid #0070A8; 
    } 
    .datagrid table thead th:first-child 
    { 
        border: none; 
    }
    .datagrid table tbody td 
    { 
        color: #00557F; 
        border-left: 1px solid #E1EEF4;
        font-size: 12px;
        font-weight: normal; 
    }
    .datagrid table tbody tr:nth-child(odd) 
    { 
        background: #E1EEf4; 
        color: #00557F; 
    }
    .datagrid table tbody td:first-child 
    { 
        border-left: none; 
    }
    .datagrid table tbody tr:last-child td 
    { 
        border-bottom: none; 
    }
    .datagrid table tfoot td div 
    { 
        border-top: 1px solid #006699;
        background: #E1EEf4;
    } 
    .datagrid table tfoot td 
    { 
        padding: 0; 
        font-size: 12px 
    } 
    .datagrid table tfoot td div
    { 
        padding: 2px; 
    }
    .datagrid table tfoot td ul 
    { 
        margin: 0; 
        padding:0; 
        list-style: none; 
        text-align: right; 
    }
    .datagrid table tfoot  li 
    { 
        display: inline; 
    }
    .datagrid table tfoot li a 
    { 
        text-decoration: none; 
        display: inline-block;  
        padding: 2px 8px; 
        margin: 1px;color: #FFFFFF;
        border: 1px solid #006699;
        -webkit-border-radius: 3px; 
        -moz-border-radius: 3px; 
        border-radius: 3px; 
        background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );
        background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );
        filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');
        background-color:#006699; }
    .datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover 
    { 
        text-decoration: none;
        border-color: #00557F; 
        color: #FFFFFF; 
        background: none; 
        background-color:#006699;
    }
    
    .datagrid table tbody td:nth-child(1)
    {
        text-align: left;
        padding: 8px;
        width: auto;
        max-width: 200px;
    }
    .datagrid table tbody td:nth-child(2)
    {
        text-align: left;
        padding-left: 5px;
        width: auto;
    }
    .datagrid table tbody td:nth-child(3)
    {
        text-align: right;
        padding-right: 5px;
        width: 100px;
    }
    .datagrid table tbody td:nth-child(4)
    {
        text-align: right;
        padding-right: 5px;
        width: 100px;
    }
    
    .datagrid table tbody tr:hover
    {
        background-color: #DFD5AF;
        cursor: pointer;
    }
    
    .value-verified
    {
        color: #008000 !important;
        font-weight: bold !important;
    }
    
    .btn-square {
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background-color:#f9f9f9;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	cursor:pointer;
	color:#666666;
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #ffffff;
        
        height: 80px;
        background-position-y: 5px;
        background-position-x: center;
        margin: 10px;
        margin-left: 0;
        padding-top: 40px;
    }
    .btn-square:hover {
            background-color:#e9e9e9;
            color: #006699;
            font-weight: bold;
    }
    .btn-square:active {
            position:relative;
            top:1px;
    }
    
    .modal-wait {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('http://i.stack.imgur.com/FhHRx.gif') 
                50% 50% 
                no-repeat;
    }

</style>

<div id="sheet-content">
    <div>
        <input type='button' id='btnPriceUpdate' value='{l s='Update prices' mod='mpupdateprices'}' class='btn-square icon-import'>
    </div>
    <div class='datagrid'>
        <table id="tblPriceContent">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check_all" checked="checked"></th>
                    <th>{l s='Product Reference' mod='mpupdateprices'}</th>
                    <th>{l s='Product Price' mod='mpupdateprices'}</th>
                    <th>{l s='Product Price Variation' mod='mpupdateprices'}</th>
                    <th class='hidden'>id product</th>
                </tr>
            </thead>
            <tbody>
                {foreach $fileContent as $row}
                    <tr>
                        <td><input type='checkbox' name='check_row[]' {if $row['id']>0}checked='checked'{/if}> &nbsp; {$row['id']}</td>
                        <td
                            {if $row['id']>0}
                                class='value-verified'
                            {/if} >
                            {$row['reference']} ({$row['match']})
                        </td>
                        <td>{$row['price']}</td>
                        <td>{$row['variation']}</td>
                        <td class='hidden'>{$row['id']}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

<div id="dialog-confirm" class="modal-custom-dialog">
  <!-- Modal content -->
  <div class="modal-dialog-content">
    <div class="modal-dialog-header">
        <span class="close" onclick="$(this).parent().parent().parent().fadeOut();"></span>
      <h2>{l s="Import" mod='mpupdateprices'}</h2>
    </div>
    <div class="modal-dialog-body">
        <p><span class="icon-info-circle" style="margin-right: 10px;"></span>{l s="This action will insert a new record. Are you sure?" mod='mpupdateprices'}</p>
    </div>
    <div class="modal-dialog-footer">
        <input type="button" value="{l s='YES' mod='mpupdateprices'}" onclick="$(this).parent().parent().parent().fadeOut(); import_prices();" class='submit-btn modal-button icon-confirm'>
        <input type="button" value="{l s='NO' mod='mpupdateprices'}" onclick="$(this).parent().parent().parent().fadeOut();" class='submit-btn modal-button icon-delete'>
    </div>
  </div>
</div>
            
<div id="dialog-import" class="modal-custom-dialog">
  <!-- Modal content -->
  <div class="modal-dialog-content">
    <div class="modal-dialog-header">
        <span class="close" onclick="$(this).parent().parent().parent().fadeOut();"></span>
      <h2>{l s="Notice" mod='mpupdateprices'}</h2>
    </div>
    <div class="modal-dialog-body">
        <p>
        {l s='Prices updated.' mod='mpupdateprices'}
        </p>
    </div>
    <div class="modal-dialog-footer">
        <input type="button" value="{l s='OK' mod='mpupdateprices'}" onclick="$(this).parent().parent().parent().fadeOut();" class='submit-btn modal-button icon-confirm'>
    </div>
  </div>
</div>

<div class='modal-wait' id='wait-anim'></div>    
    
<script type="text/javascript">
    $body = $("body");

    $(document).ready(function()
    {
        $("#file_upload").attr('accept', '.csv, .xls');
        $("#btnPriceUpdate").on("click",function()
        {
            $("#dialog-confirm").show();
        });
        $("input[name='chkPriceVariations']").on("click",function()
        {
            var value = $(this).val();
            console.log ("pricetax: " + value);
            if(value==='0')
            {
                $("#fieldset_1_1").fadeOut();
            }
            else
            {
                $("#fieldset_1_1").fadeIn();
            }
        });
        {if $UpdatePricesClass->has_price_variations}
            $("#fieldset_1_1").show();   
        {else}
            $("#fieldset_1_1").hide();
        {/if}
        
        $("#sheet-content").detach().appendTo($("#fieldset_3_3").find(".form-wrapper"));
        
        $("input[name='submit_form'").on("click",function()
        {
           $body.addClass("loading");
        });
        
        $("#check_all").on("click",function(){
            var table= $("#tblPriceContent");
            $('td input:checkbox',table).prop('checked',this.checked);
        });
    });
    
    function import_prices()
        {
            var table = $("#tblPriceContent");
            var rows  = $(table).find("tbody").children();
            var tax = Number({$Tax});
            //alert("ROWS: " + rows.length);
            var resultObj = new Array();
            
            $('tbody td input:checkbox',table).each(function(){
                //alert(this.checked);
                var row = $(this).parent().parent();
                //alert("row: " + $(row).html());
                if(this.checked)
                {
                    var cols = $(row).children();
                    //alert("ROW " + i + ", cols: " + cols.length);

                    //get order reference
                    var product_id = Number($(cols[4]).text());
                    var price      = Number($(cols[3]).text());
                    
                    var obj = new Object();
                    obj.product_id = product_id;
                    obj.price  = price;
                    resultObj.push(obj);
                } 
            });
            var jsonString= JSON.stringify(resultObj);
            //alert("JSON: " + jsonString);
            $("#wait-anim").fadeIn();
            $.ajax(
            {
                url: "../modules/mpupdateprices/ajax/updatePrice.php",
                type: "post",
                data: 
                {
                    json : jsonString,
                    tax  : tax
                },
                success: function(msg)
                    {
                        $("#wait-anim").fadeOut();
                        $("#dialog-import").show();
                    }
            });
        }
</script>