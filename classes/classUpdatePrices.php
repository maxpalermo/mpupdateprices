<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of classUpdatePrices
 *
 * @author massimiliano
 */

/** Include PHPExcel */
if (!class_exists('PHPExcel')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "PHPExcel.php";
}

class classUpdatePrices{
    
    public $id_update_prices;
    public $col_reference;
    public $col_price;
    public $row_start_from;
    public $match_type;
    public $is_tax_included;
    public $has_price_variations;
    public $variation_method;
    public $variation_amount;
    public $variation_value;
    public $variation_round_method;
    public $variation_round_amount;
    
    public function __construct() {
        $this->get();
    }
    
    public function insert()
    {
        $db = Db::getInstance();
        $db->insert("mp_update_prices",
        [
            "id_update_prices"=>1,
            "col_reference"=>$this->col_reference,
            "col_price"=>$this->col_price,
            "row_start_from"=>$this->row_start_from,
            "match_type"=>$this->match_type,
            "is_tax_included"=>$this->is_tax_included,
            "has_price_variations"=>$this->has_price_variations,
            "variation_method"=>$this->variation_method,
            "variation_amount"=>$this->variation_amount,
            "variation_value"=>$this->variation_value,
            "variation_round_method"=>$this->variation_round_method,
            "variation_round_amount"=>$this->variation_round_amount
        ],TRUE,FALSE,Db::REPLACE);
    }
    
    public function update()
    {
        $this->insert();
    }
    
    public function delete()
    {
        $db = Db::getInstance();
        $db->delete("mp_update_prices","id_update_prices = " . $this->id_update_prices);
    }
    
    public function get()
    {
        $db = Db::getInstance();
        $query = "select * from " . _DB_PREFIX_ . "mp_update_prices";
        $res = $db->getRow($query);
        
        if(!empty($res))
        {
            $this->id_update_prices = (int)$res['id_update_prices'];
            $this->col_reference = (int)$res['col_reference'];
            $this->col_price = (int)$res['col_price'];
            $this->row_start_from = (int)$res['row_start_from'];
            $this->match_type = (int)$res['match_type'];
            $this->is_tax_included = (int)$res['is_tax_included'];
            $this->has_price_variations = (int)$res['has_price_variations'];
            $this->variation_method = (int)$res['variation_method'];
            $this->variation_amount = (int)$res['variation_amount'];
            $this->variation_value = (float)$res['variation_value'];
            $this->variation_round_method = (int)$res['variation_round_method'];
            $this->variation_round_amount = (int)$res['variation_round_amount'];
        }
        else
        {
            $this->id_update_prices = 0;
            $this->col_reference = 0;
            $this->col_price = 0;
            $this->row_start_from = 0;
            $this->match_type=0;
            $this->is_tax_included = FALSE;
            $this->has_price_variations = FALSE;
            $this->variation_method = 0;
            $this->variation_amount = 0;
            $this->variation_value = 0;
            $this->variation_round_method = 0;
            $this->variation_round_amount = 0;
        }
        return $res;
    }
    
    public static function roundNum($num, $nearest)
    { 
        return round($num / $nearest) * $nearest; 
    } 
    
    public function getExcel($fileName)
    {
        // Read from Excel5 (.xls) template
        $inputFileType = 'Excel5';
        $objPHPExcel = new PHPExcel_Reader_Excel5();
        $objPHPExcel->setReadDataOnly(true);
        $objPHPExcel->load($fileName);
        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        
        $objExcel = $objReader->load($fileName);
        $sheet = $objExcel->getActiveSheet()->toArray(TRUE,TRUE);
        
        return $sheet;
        
    }
    
    public function getCSV($file)
    {
        // Read from CSV (.csv) template
        $objPHPExcel = new PHPExcel_Reader_CSV();
 
    }
    
    
    
}

class ExcelContentFormat 
{
    private $header;
    private $rows;
    private $col;
    private $row;
    
    public function __construct() 
    {
        $this->header = [];
        $this->rows = [];
        $this->row = [];
        $this->col = "";
    }
    
    public function newRow()
    {
        $this->row = [];
    }
    
    public function addCol($value)
    {
        $this->col = $value;
        $this->row[] = $value;
    }
    
    public function addHeader()
    {
        $this->header[] = $this->row;
        $this->row = [];
    }
    
    public function addContentRow()
    {
        $this->rows[] = $this->row;
        $this->row = [];
    }
    
    public function getHeader()
    {
        return $this->header;
    }
    
    public function getContentRows()
    {
       return $this->rows;
    }
}