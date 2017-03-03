<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . "classUpdatePrices.php";

if (!defined('_PS_VERSION_')){exit;}
 
class MpUpdatePrices extends Module
{
  public function __construct()
  {
    $this->name = 'mpupdateprices';
    $this->tab = 'administration';
    $this->version = '1.0.0';
    $this->author = 'mpsoft';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Massive update product prices');
    $this->description = $this->l('With this module, you are able to import an Excel or CSV file to update product prices.');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
  }
  
  public function install()
    {
      if (Shop::isFeatureActive())
      {
        Shop::setContext(Shop::CONTEXT_ALL);
      }

      if (!parent::install() ||
                !$this->registerHook('displayBackOfficeHeader') ||
                !$this->installTab() ||
                !$this->installSQL()
      )
      {
        return false;
      }
      return true;
    }
    
    public function uninstall()
    {
      if (!parent::uninstall() ||
              !$this->uninstallSQL() ||
              !$this->uninstallTab())
      {
        return false;
      }
      return true;
    }
    
    public function installTab()
    {
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = 'AdminMpUpdatePrices';
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang)
            {
                    $tab->name[$lang['id_lang']] = 'MP Update Prices';
            }
            $tab->id_parent = (int)Tab::getIdFromClassName('AdminCatalog');
            $tab->module = $this->name;
            return $tab->add();
    }

    public function uninstallTab()
    {
            $id_tab = (int)Tab::getIdFromClassName('AdminMpUpdatePrices');
            if ($id_tab)
            {
                    $tab = new Tab($id_tab);
                    return $tab->delete();
            }
            else
            {
                    return false;
            }
    }
    
    private function installSQL()
    {
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . "sql" . DIRECTORY_SEPARATOR . "install.sql";
        $sql = explode(";",file_get_contents($filename));
        if(empty($sql)){return FALSE;}
        foreach($sql as $query)
        {
            if(!empty($query))
            {
                $query = str_replace("{_DB_PREFIX_}", _DB_PREFIX_, $query);
                $db = Db::getInstance();
                $result = $db->execute($query);
                if(!$result){return FALSE;}
            }
        }
        return TRUE;
    }
    
    private function uninstallSQL()
    {
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . "sql" . DIRECTORY_SEPARATOR . "uninstall.sql";
        $sql = explode(";",file_get_contents($filename));
        if(empty($sql)){return FALSE;}
        foreach($sql as $query)
        {
            if(!empty($query))
            {
                $query = str_replace("{_DB_PREFIX_}", _DB_PREFIX_, $query);
                $db = Db::getInstance();
                $result = $db->execute($query);
                if(!$result){return FALSE;}
            }
        }
        return TRUE;
    }
    
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
    }
    
    
}