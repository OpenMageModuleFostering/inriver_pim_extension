<?php

class InRiver_Catalog_Model_Product_Attribute_Media_Api extends Mage_Catalog_Model_Product_Attribute_Media_Api {

    public function create($productId, $data, $store = null, $identifierType = null) {
        Mage::dispatchEvent('inriver_create_product_attribute_media_api_before', array('productId' => $productId, 'data' => $data));
        $fileName = parent::create($productId, $data, $store, $identifierType);
        Mage::dispatchEvent('inriver_create_product_attribute_media_api_after', array('fileName' => $fileName));
        return $fileName;
    }

    public function update($productId, $file, $data, $store = null, $identifierType = null) {
        Mage::dispatchEvent('inriver_update_product_attribute_media_api_before', array('productId' => $productId, 'data' => $data));
        $isUpdated = parent::update($productId, $file, $data, $store, $identifierType);
        Mage::dispatchEvent('inriver_update_product_attribute_media_api_after', array('isUpdated' => $isUpdated));
        return $isUpdated;
    }

    public function items($productId, $store = null, $identifierType = null) {
        $list = parent::items($productId, $store, $identifierType);
        Mage::dispatchEvent('inriver_list_product_attribute_media_api_after', array('list' => $list));
        return $list;
    }
}