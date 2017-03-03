<?php
/**
* 2007-2016 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "classUpdatePrices.php";

class AdminMpUpdatePricesController extends ModuleAdminController 
{
    private $lang_id;
    private $form;
    private $list;
    private $UpdatePrices;
    private $fileContent;
    
    public function __construct()
    {
            $this->bootstrap = true;
            $this->context = Context::getContext();
            $this->name = 'mpupdateprices';
            $this->displayName = 'MP Update Prices';

            parent::__construct();
            
            $this->lang_id = Context::getContext()->language->id;
            $this->UpdatePrices = new classUpdatePrices();
    }

    public function initToolbar()
    {
            parent::initToolbar();
            unset($this->toolbar_btn['new']);
    }

    public function postProcess()
    {
        
    }

    public function setMedia()
    {
            parent::setMedia();
            //add CSS and JS
            //$this->addJqueryUI('ui.datepicker');
            //$this->addJS(_PS_MODULE_DIR_ . 'mpupdateprices/views/js/datepicker_IT.js');
            $this->addCSS(_CSS_ . 'dialog.css');
    }
        
    public function initContent() 
    {    

        parent::initContent();
        
        $this->UpdatePrices->get();
        
        if (Tools::isSubmit('submit_form'))
        {
            $this->UpdatePrices->col_reference = Tools::getValue("txtColReference");
            $this->UpdatePrices->col_price = Tools::getValue("txtColPrice");
            $this->UpdatePrices->row_start_from = Tools::getValue("txtRowStart");
            $this->UpdatePrices->match_type = Tools::getValue("selMatchType");
            $this->UpdatePrices->is_tax_included = Tools::getValue("chkPriceTax");
            $this->UpdatePrices->has_price_variations = Tools::getValue("chkPriceVariations");
            $this->UpdatePrices->variation_method = Tools::getValue("selVariationMethod");
            $this->UpdatePrices->variation_amount = Tools::getValue("selVariationAmount");
            $this->UpdatePrices->variation_value = Tools::getValue("txtVariationValue");
            $this->UpdatePrices->variation_round_method = Tools::getValue("selVariationRoundMethod");
            $this->UpdatePrices->variation_round_amount = Tools::getValue("selVariationRoundAmount");
            
            $this->UpdatePrices->insert();
            
            if(isset($_FILES['file_upload']['name']))
            {
                $fileName = $_FILES['file_upload']['name'];
                $tmpName = $_FILES['file_upload']['tmp_name'];
                $split = explode(".", $fileName);
                $ext = end($split);
                $formula = "";
                
                switch($ext)
                {
                    case "xls":
                        $arrContent = [];
                        $this->fileContent = $this->UpdatePrices->getExcel($tmpName);
                        for($i=0;$i<$this->UpdatePrices->row_start_from;$i++)
                        {
                            array_shift($this->fileContent);
                        }
                        
                        $db = Db::getInstance();
                        $sqlRef = new DbQueryCore();
                        
                        $VariationMethod = Tools::getValue("selVariationMethod");
                        $VariationAmount = Tools::getValue("selVariationAmount");
                        $VariationValue = (float)Tools::getValue("txtVariationValue");
                        $VariationRoundValue = (float)Tools::getValue("selVariationRoundAmount")/100;
                        $VariationRoundMethod = Tools::getValue("selVariationRoundMethod");
                        
                        foreach($this->fileContent as $row)
                        {
                            $reference = $row[$this->UpdatePrices->col_reference];
                            $price = $row[$this->UpdatePrices->col_price];
                            $sqlRef = new DbQueryCore();
                            $sqlRef
                                    ->select("id_product")
                                    ->select("reference")
                                    ->from("product");
                            switch($this->UpdatePrices->match_type)
                            {
                                case 0:
                                    $sqlRef->where("reference = '$reference'");
                                    break;
                                case 1:
                                    $sqlRef->where("reference like '%$reference'");
                                    break;
                                case 2:
                                    $sqlRef->where("reference like '$reference%'");
                                    break;
                                case 3:
                                    $sqlRef->where("reference like '%$reference%'");
                                    break;
                                default:
                                    $sqlRef->where("reference = '$reference'");
                                    break;
                            }
                                    
                            $result = $db->getRow($sqlRef);
                            if(empty($result))
                            {
                                $arrContent[]= [
                                    "id"=>0,
                                    "reference"=>$reference,
                                    "price"=>0,
                                    "variation"=>0,
                                    "match"=>''
                                ];
                            }
                            else
                            {
                                //Get formula price variations
                                if(Tools::getValue("chkPriceVariations"))
                                {
                                    $amount = 0;
                                    $sum = 0;
                                    $round = 0;
                                    
                                    if($VariationAmount==0) //value
                                    {
                                        $amount = $VariationValue;
                                    }
                                    else //percent
                                    {
                                        $amount = $price * $VariationValue /100;
                                    }
                                    
                                    if($VariationMethod==0) //increase
                                    {
                                        $sum = $price + $amount;
                                    }
                                    else //decrease
                                    {
                                        $sum = $price - $amount;
                                    }
                                   
                                    if($VariationRoundMethod==0) //up
                                    {
                                        $round = ceil($sum/$VariationRoundValue)*$VariationRoundValue;
                                    }
                                    else if($VariationRoundMethod==1) //down
                                    {
                                        $round = floor($sum/$VariationRoundValue)*$VariationRoundValue;
                                    }
                                    else // no round
                                    {
                                        $round = $sum;
                                    }
                                }
                                
                                $arrContent[]= [
                                    "id"=>(int)$result['id_product'],
                                    "reference"=>$reference,
                                    "price"=>$price,
                                    "variation"=>  number_format($round,2),
                                    "match"=>$result['reference']
                                ];
                            }
                            
                        }
                        $this->fileContent = $arrContent;
                        
                        break;
                    case "csv":
                        break;
                    default:
                        break;
                }                
            }
            
        }
        
        //Display Page        
        $smarty = $this->context->smarty;
        $this->form = $this->renderHelperForm();
        $smarty->assign('UpdatePricesClass', $this->UpdatePrices);
        $smarty->assign('fileContent',$this->fileContent);
        $smarty->assign('Tax',Tools::getValue("chkPriceTax"));
        $content = $this->form . $this->list . $smarty->fetch(_PS_MODULE_DIR_ . 'mpupdateprices/views/templates/admin/import_page.tpl');
        $smarty->assign('content', $content);
    } 

    public function renderHelperForm()
    {
        $this->UpdatePrices->get();
        $lang_id = $this->lang_id;
        $fields_form = [];
        $fields_form[0]['form'] = array(
            'legend' => array(       
                    'title' => $this->l('MP Update Prices'),       
                    'image' => '../modules/mpupdateprices/logo.png'   
                ),   
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Col idx product reference:'),
                    'desc' => $this->l('Choose the column index of product reference'),
                    'name' => 'txtColReference',
                    'required' => true, 
                    'class' => 'input fixed-width-sm'
                    ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Col idx product price:'),
                    'desc' => $this->l('Choose the column index of product price'),
                    'name' => 'txtColPrice',
                    'required' => true,  
                    'class' => 'input fixed-width-sm'
                    ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Reading start from row:'),
                    'desc' => $this->l('Choose the row index to start reading data'),
                    'name' => 'txtRowStart',
                    'required' => true,  
                    'class' => 'input fixed-width-sm'
                    ),
                array(
                'type' => 'select',                              
                    'label' => $this->l('Match:'),
                    'desc' => $this->l('Choose match type'), 
                    'name' => 'selMatchType',                     
                    'required' => true,
                    'options' => array(
                        'query' => $this->getMatchOptions(),
                        'id' => 'id',                           
                        'name' => 'value'                       
                    )
                    ),
                array(        
                    'type' => 'switch',
                    'label' => $this->l('Price include taxes?'),
                    'desc' => $this->l('The price imported has tax included'),
                    'name' => 'chkPriceTax',
                    'is_bool' => true,
                    'values' => 
                        [
                            [
                                'id' => 'tax_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ],
                            [
                                'id' => 'tax_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            ]
                        ]
                    ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Price has variations:'),
                    'desc' => $this->l('It indicates whether the price has to be changed before being imported.'),
                    'name' => 'chkPriceVariations',
                    'is_bool' => true,
                    'values' => 
                        [
                            [
                                'id' => 'var_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ],
                            [
                                'id' => 'var_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            ]
                        ]                    
                    )
                )
            );
        
        $fields_form[1]['form'] = array(
            'legend' => array(       
                    'title' => $this->l('Price Variations management'),
                    'image' => '../modules/mpupdateprices/img/price.png'   
                
                ),  
            'input' => [
                [
                    'type' => 'select',                              
                    'label' => $this->l('Variation method:'),
                    'desc' => $this->l('Choose price variation method'), 
                    'name' => 'selVariationMethod',                     
                    'required' => true,
                    'options' => array(
                        'query' => $this->getVariationPriceMethodOptions(),
                        'id' => 'id',                           
                        'name' => 'value'                       
                    )
                ],
                [
                    'type' => 'select',                              
                    'label' => $this->l('Variation amount:'),
                    'desc' => $this->l('Choose price variation amount'), 
                    'name' => 'selVariationAmount',                     
                    'required' => true,
                    'options' => array(
                        'query' => $this->getVariationPriceAmountOptions(),
                        'id' => 'id',                           
                        'name' => 'value'                       
                    )
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Variation value:'),
                    'desc' => $this->l('Insert the variation value'),
                    'name' => 'txtVariationValue',
                    'required' => true,    
                    'class' => 'input fixed-width-md'                    
                ],
                [
                    'type' => 'select',                              
                    'label' => $this->l('Variation round method:'),
                    'desc' => $this->l('Choose round variation methos'), 
                    'name' => 'selVariationRoundMethod',                     
                    'required' => true,
                    'options' => array(
                        'query' => $this->getVarationRoundMethodOptions(),
                        'id' => 'id',                           
                        'name' => 'value'                       
                    )
                ],
                [
                    'type' => 'select',                              
                    'label' => $this->l('Variation round amount:'),
                    'desc' => $this->l('Choose round variation amount'), 
                    'name' => 'selVariationRoundAmount',                     
                    'required' => true,
                    'options' => array(
                        'query' => $this->getVarationRoundAmountOptions(),
                        'id' => 'id',                           
                        'name' => 'value'                       
                    )
                ],
                
            ]
        );
        
        $fields_form[2]['form'] = [
            'legend' => array(       
                    'title' => ''
                ), 
            'input' => [
                [
                    'type' => 'file',
                    'label' => $this->l('Upload document:'),
                    'name' => 'file_upload',
                    'id' => 'file_upload',
                    'display_image' => false,
                    'required' => TRUE,
                    'desc' => $this->l('Upload your document')
                ],
            ],
            'submit' => 
                [
                    'title' => $this->l('GO'),       
                    'class' => 'btn btn-default pull-right',
                    'name'  => 'submit_form',
                    'icon'  => 'icon-mail-forward'
                ]
            ];
        $fields_form[3]['form'] = array(
            'legend' => array(       
                    'title' => $this->l('Price Update Table'),
                    'image' => '../modules/mpupdateprices/img/price-table.png'   
                
                ),  
            'input' => [
                [
                    'type' => 'hidden',
                    'name' => 'txtDummy',
                ],
            ]
        );
            
        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminMpUpdatePrices');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $lang_id;
        $helper->allow_employee_form_lang = $lang_id;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit_form';
        $helper->toolbar_btn = array(
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminMpUpdatePrices'),
                'desc' => $this->l('Back to list')
            )
        );
        
        $helper->fields_value['txtColReference'] = $this->UpdatePrices->col_reference;
        $helper->fields_value['txtColPrice'] = $this->UpdatePrices->col_price;
        $helper->fields_value['txtRowStart'] = $this->UpdatePrices->row_start_from;
        $helper->fields_value['selMatchType'] = $this->UpdatePrices->match_type;
        $helper->fields_value['chkPriceTax'] = $this->UpdatePrices->is_tax_included;
        $helper->fields_value['chkPriceVariations'] = $this->UpdatePrices->has_price_variations;
        $helper->fields_value['selVariationMethod'] = $this->UpdatePrices->variation_method;
        $helper->fields_value['selVariationAmount'] = $this->UpdatePrices->variation_amount;
        $helper->fields_value['txtVariationValue'] = $this->UpdatePrices->variation_value;
        $helper->fields_value['selVariationRoundMethod'] = $this->UpdatePrices->variation_round_method;
        $helper->fields_value['selVariationRoundAmount'] = $this->UpdatePrices->variation_round_amount;
        
        $html = $helper->generateForm($fields_form);

        return  $html;
    }
    
    public function getVariationPriceMethodOptions()
    {    
        $options = array(
            array(
              'id' => 0,
              'value' => $this->l('Increase')
            ),
            array(
              'id' => 1,
              'value' => $this->l('Decrease')
            ),
        );
        
        return $options;
    }
    
    public function getMatchOptions()
    {    
        $options = array(
            array(
              'id' => 0,
              'value' => $this->l('Exact')
            ),
            array(
              'id' => 1,
              'value' => $this->l('Start')
            ),
            array(
              'id' => 2,
              'value' => $this->l('End')
            ),
            array(
              'id' => 3,
              'value' => $this->l('Contains')
            ),
        );
        
        return $options;
    }
    
    public function getVariationPriceAmountOptions()
    {
        $options = array(
            array(
              'id' => 0,
              'value' => $this->l('Value')
            ),
            array(
              'id' => 1,
              'value' => $this->l('Percent')
            ),
        );
        
        return $options;
    }
    
    public function getVarationRoundMethodOptions()
    {
        $options = array(
            array(
              'id' => 0,
              'value' => $this->l('Up')
            ),
            array(
              'id' => 1,
              'value' => $this->l('Down')
            ),
            array(
              'id' => 2,
              'value' => $this->l('None')
            ),
        );
        
        return $options;
    }
    
    public function getVarationRoundAmountOptions()
    {
        $options = array(
            array(
              'id' => 5,
              'value' => $this->l('5 cents')
            ),
            array(
              'id' => 10,
              'value' => $this->l('10 cents')
            ),
            array(
              'id' => 25,
              'value' => $this->l('25 cents')
            ),
            array(
              'id' => 50,
              'value' => $this->l('50 cents')
            ),
            array(
              'id' => 100,
              'value' => $this->l('Integer')
            ),
        );
        
        return $options;
    }
}
