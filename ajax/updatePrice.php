<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

header('Access-Control-Allow-Origin: *');

require_once(dirname(__FILE__).'/../../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../../init.php');

$json = Tools::getValue("json");
$tax  = Tools::getValue("tax");

$prices = json_decode($json);

foreach($prices as $row)
{
    $product = new ProductCore($row->product_id);
    $price = 0;
    if($tax)
    {
        $taxRate = $product->getTaxesRate();
        $price = round(($row->price*100)/(100+$taxRate),6);
    }
    else
    {
        $taxRate = "Included";
        $price = round($row->price,6);
    }
    
    print_r($row);
    print "tax: $tax , rate: $taxRate, update price = " . floatval($price);
    $product->price = floatval($price);
    $product->update();
}