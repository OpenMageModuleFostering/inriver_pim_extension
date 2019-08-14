<?php

class InRiver_Catalog_Model_Product_Attribute_Api extends Mage_Catalog_Model_Product_Attribute_Api {

    public function create($data) {
        Mage::dispatchEvent('inriver_create_product_attribute_api_before', array('data' => $data));
        $id = parent::create($data);
        Mage::dispatchEvent('inriver_create_product_attribute_api_after', array('id' => $id));
        return $id;
    }

    public function remove($attribute) {
        Mage::dispatchEvent('inriver_remove_product_attribute_api_before', array('attribute' => $attribute));
        $isRemoved = parent::remove($attribute);
        Mage::dispatchEvent('inriver_remove_product_attribute_api_after', array('isRemoved' => $isRemoved));
        return $isRemoved;
    }

    public function options($attribute, $store = null) {
        Mage::dispatchEvent('inriver_options_attribute_api_before', array('attribute' => $attribute));
        $options = parent::options($attribute, $store);
        Mage::dispatchEvent('inriver_options_attribute_api_after', array('options' => $options));
        return $options;
    }

    public function addOption($attribute, $data) {
        Mage::dispatchEvent('inriver_add_option_attribute_api_before', array('attribute' => $attribute));
        $isAdded = parent::addOption($attribute, $data);
        Mage::dispatchEvent('inriver_add_option_attribute_api_after', array('isAdded' => $isAdded));
        return $isAdded;
    }

    public function removeOption($attribute, $optionId) {
        Mage::dispatchEvent('inriver_remove_option_attribute_api_before', array('attribute' => $attribute));
        $isRemoved = parent::removeOption($attribute, $optionId);
        Mage::dispatchEvent('inriver_remove_option_attribute_api_after', array('isRemoved' => $isRemoved));
        return $isRemoved;
    }

    public function listAllAttributes() {
        $sqlquery = "select attribute_id, attribute_code from eav_attribute where entity_type_id IN (select entity_type_id from eav_entity_type where entity_type_code = 'catalog_product')";

        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $readresult = $write->query($sqlquery);

        while ($row = $readresult->fetch()) {

            $groups[] = array($row['attribute_id'], $row['attribute_code']);
        }

        Mage::dispatchEvent('inriver_listAllAttributes_after', array('groups' => $groups));
        return $groups;
    }

    public function updateAttributeOptions($attributeId, $options) {
        Mage::dispatchEvent('inriver_updateAttributeOptions_before', array('attributeId' => $attributeId, 'options' => $options));
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');

        foreach ($options as $option) {
            $readresult = $write->query("UPDATE `eav_attribute_option_value` SET value='" . $option['value'] . "' where `option_id`=" . $option['option_id'] . " and store_id=" . $option['store_id']);
        }
        return true;
    }
}