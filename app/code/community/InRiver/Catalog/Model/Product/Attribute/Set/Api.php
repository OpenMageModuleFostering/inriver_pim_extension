<?php

class InRiver_Catalog_Model_Product_Attribute_Set_Api extends Mage_Catalog_Model_Product_Attribute_Set_Api {

    public function create($attributeSetName, $skeletonSetId) {
        Mage::dispatchEvent('inriver_create_product_attribute_set_api_before', array('attributeSetName' => $attributeSetName, 'skeletonSetId' => $skeletonSetId));
        $id = parent::create($attributeSetName, $skeletonSetId);
        Mage::dispatchEvent('inriver_create_product_attribute_set_api_after', array('id' => $id));
        return $id;
    }
        
    public function remove($attributeSetId, $forceProductsRemove = false) {
        Mage::dispatchEvent('inriver_remove_product_attribute_set_api_before', array('attributeSetId' => $attributeSetId));
        $isRemoved = parent::remove($attributeSetId, $forceProductsRemove);
        Mage::dispatchEvent('inriver_remove_product_attribute_set_api_after', array('isRemoved' => $isRemoved));
        return $isRemoved;
    }
    
    public function items() {
        $items = parent::items();
        Mage::dispatchEvent('inriver_list_product_attribute_set_api_after', array('items' => $items));
        return $items;
    }

    public function groupAdd($attributeSetId, $groupName) {
        Mage::dispatchEvent('inriver_attribute_set_group_add_api_before', array('attributeSetId' => $attributeSetId, 'groupName' => $groupName));
        $id = parent::groupAdd($attributeSetId, $groupName);
        Mage::dispatchEvent('inriver_attribute_set_group_add_api_after', array('id' => $id));
        return $id;
    }

    public function groupList($attributeSetId) {
        Mage::dispatchEvent('inriver_create_attribute_set_group_list_api_before', array('attributeSetId' => $attributeSetId));
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $readresult = $write->query("SELECT `attribute_group_id` , `attribute_group_name` FROM `eav_attribute_group` WHERE `attribute_set_id` =" . $attributeSetId);

        while ($row = $readresult->fetch()) {
            $groups[] = $row['attribute_group_name'];
            $groups[] = $row['attribute_group_id'];
        }
        Mage::dispatchEvent('inriver_create_attribute_set_group_list_api_after', array('group_id' => $groups));
        return $groups;
    }
    
    public function attributeAdd($attributeId, $attributeSetId, $attributeGroupId = null, $sortOrder = '0') {
        Mage::dispatchEvent('inriver_attribute_set_attribute_add_api_before', array('attributeId' => $attributeId, 'attributeSetId' => $attributeSetId, 'attributeGroupId' => $attributeGroupId, 'sortOrder' => $sortOrder));
        $isAdded = parent::attributeAdd($attributeId, $attributeSetId, $attributeGroupId, $sortOrder);
        Mage::dispatchEvent('inriver_attribute_set_attribute_add_api_after', array('isAdded' => $isAdded));
        return $isAdded;
    }

    public function attributesForGroupId($attributeGroupId) {
        Mage::dispatchEvent('inriver_create_attribute_set_attributesForGroupId_before', array('attributeSetId' => $attributeGroupId));

        $attributeCodes = array();
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                ->setAttributeGroupFilter($attributeGroupId)
                ->addVisibleFilter()
                ->checkConfigurableProducts()
                ->load();

        if ($attributes->getSize() > 0) {
            foreach ($attributes->getItems() as $attribute) {
                $attributeCodes[] = $attribute->getAttributeCode();
            }
        }
        Mage::dispatchEvent('inriver_create_attribute_set_attributesForGroupId_after', array('group_id' => $attributeCodes));
        return $attributeCodes;
    }
}