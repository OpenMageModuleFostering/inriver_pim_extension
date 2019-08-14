<?php

class InRiver_Catalog_Model_Store_Api extends Mage_Core_Model_Store_Api
{
    public function items()
    {
        $items = parent::items();
        Mage::dispatchEvent('inriver_list_store_api_after', array('items' => $items));
        return $items;        
    }
}
