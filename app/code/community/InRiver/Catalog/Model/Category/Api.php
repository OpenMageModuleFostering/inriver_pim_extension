<?php

class InRiver_Catalog_Model_Category_Api extends Mage_Catalog_Model_Category_Api {

    public function tree($parentId = null, $store = null) {
        $tree = parent::tree($parentId, $store);
        Mage::dispatchEvent('inriver_tree_category_api_after', array('tree' => $tree));
        return $tree;
    }
        
    public function create($parentId, $catData, $store = null) {
        Mage::dispatchEvent('inriver_create_category_api_before', array('parentId' => $parentId, 'catData' => $catData));
        $categoryId = parent::create($parentId, $catData, $store);
        Mage::dispatchEvent('inriver_create_category_api_after', array('categoryId' => $categoryId));
        return $categoryId;
    }
    
    public function delete($categoryId) {
        Mage::dispatchEvent('inriver_delete_category_api_before', array('categoryId' => $categoryId));
        $isRemoved = parent::delete($categoryId);
        Mage::dispatchEvent('inriver_delete_category_api_after', array('isRemoved' => $isRemoved));
        return $isRemoved;
    }
    
    public function removeProduct($categoryId, $productId, $identifierType = null) {
        Mage::dispatchEvent('inriver_remove_product_category_api_before', array('categoryId' => $categoryId, 'productId' => $productId));
        $isRemoved = parent::removeProduct($categoryId, $productId, $identifierType);
        Mage::dispatchEvent('inriver_remove_product_category_api_after', array('isRemoved ' => $isRemoved ));
        return $isRemoved ;
    }

    public function info($categoryId, $store = null, $attributes = null) {
        $info = parent::info($categoryId, $store, $attributes);
        Mage::dispatchEvent('inriver_info_category_api_after', array('categoryId' => $categoryId));
        return $info;
    }
    
    public function assignProduct($categoryId, $productId, $position = null, $identifierType = null) {
        Mage::dispatchEvent('inriver_assign_product_category_api_before', array('categoryId' => $categoryId, 'productId' => $productId));
        $isAssigned = parent::assignProduct($categoryId, $productId, $position, $identifierType);
        Mage::dispatchEvent('inriver_assign_product_category_api_after', array('isAssigned' => $isAssigned));
        return $isAssigned;
    }
}