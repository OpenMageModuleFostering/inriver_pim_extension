<?php

class InRiver_Catalog_Model_Product_Api extends Mage_Catalog_Model_Product_Api {

    public function create($type, $set, $sku, $productData, $store = null) {
        Mage::dispatchEvent('inriver_create_product_api_before', array('type' => $type, 'set' => $set, 'sku' => $sku, 'productData' => $productData));
        $id = parent::create($type, $set, $sku, $productData, $store);
        Mage::dispatchEvent('inriver_create_product_api_after', array('id' => $id));
        return $id;
    }

    public function update($productId, $productData, $store = null, $identifierType = null) {
        Mage::dispatchEvent('inriver_update_product_api_before', array('productId' => $productId, 'productData' => $productData));
        $isUpdated = parent::update($productId, $productData, $store, $identifierType);
        Mage::dispatchEvent('inriver_update_product_api_after', array('isUpdated' => $isUpdated));
        return $isUpdated;
    }

    public function delete($productId, $identifierType = null) {
        Mage::dispatchEvent('inriver_delete_product_api_before', array('productId' => $productId));
        $isDeleted = parent::delete($productId, $identifierType);
        Mage::dispatchEvent('inriver_delete_product_api_after', array('isDeleted' => $isDeleted));
        return $isDeleted;
    }

    public function info($productId, $store = null, $attributes = null, $identifierType = null) {
        Mage::dispatchEvent('inriver_info_product_api_before', array('productId' => $productId, 'store' => $store));
        $productInfo = parent::info($productId, $store, $attributes, $identifierType);
        Mage::dispatchEvent('inriver_info_product_api_after', array('productInfo' => $productInfo));
        return $productInfo;
    }

    public function items($filters = null, $store = null) {
        $items = parent::items($filters, $store);
        Mage::dispatchEvent('inriver_list_product_api_after', array('items' => $items));
        return $items;
    }
    
    public function defineSuperAttributesForProduct($productId, $attributeArray) {
        Mage::dispatchEvent('inriver_define_super_attribute_before', array('productId' => $productId, 'attributeArray' => $attributeArray));

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $counter = 0;

        foreach ($attributeArray as $attributeId) {
            $sqlquery1 = "SELECT COUNT(*) AS CNT FROM `catalog_product_super_attribute` WHERE product_id = '$productId' AND attribute_id = '$attributeId'";
            $readresult1 = $write->query($sqlquery1);
            $row = $readresult1->fetch();

            if ($row['CNT'] == 0) {
                $sqlquery2 = "INSERT INTO catalog_product_super_attribute (product_id, attribute_id, position) VALUES ('$productId', '$attributeId', '@counter')";
                $readresult2 = $write->query($sqlquery2);
                $counter++;
            }
        }
        Mage::dispatchEvent('inriver_define_super_attribute_after');
    }

    public function addSuperLink($productId, $childIdArray) {
        Mage::dispatchEvent('inriver_add_super_link_before', array('productId' => $productId, 'childIdArray' => $childIdArray));

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');

        foreach ($childIdArray as $childId) {
            $sqlquery = "INSERT INTO `catalog_product_super_link` ( `product_id` , `parent_id` ) VALUES ( '$childId', '$productId')";
            $write->query($sqlquery);
        }

        Mage::dispatchEvent('inriver_add_super_link_after');
    }

    public function getProductFromEntityId($entityId) {
        Mage::dispatchEvent('inriver_get_product_api_before', array('entityId' => $entityId));
        $products = Mage::getModel('catalog/product')->getCollection();
        $products->addAttributeToFilter('inriver_entity_id', $entityId);
        $products->addAttributeToSelect('*');
        $products->load();
        foreach ($products as $val) {
            $productsArray[] = $val->getData();
        }

        Mage::dispatchEvent('inriver_get_product_api_after', array('productsArray' => $productsArray));
        return $productsArray;
    }
}