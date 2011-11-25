<?php
/**
 * Copyright © 2011 
 * netz98 new media GmbH. All rights reserved. 
 * 
 * The use and redistribution of this software, either compiled or uncompiled, 
 * with or without modifications are permitted provided that the 
 * following conditions are met:
 * 
 * 1. Redistributions of compiled or uncompiled source must contain the above 
 * copyright notice, this list of the conditions and the following disclaimer: 
 *
 * 2. All advertising materials mentioning features or use of this software must 
 * display the following acknowledgement: 
 * "This product includes software developed by the netz98 new media GmbH, Mainz."
 *
 * 3. The name of the netz98 new media GmbH may not be used to endorse or promote 
 * products derived from this software without specific prior written permission.
 *
 * 4. License holders of the netz98 new media GmbH are only permitted to 
 * redistribute altered software, if this is licensed under conditions that contain 
 * a copyleft-clause. 
 *
 * This software is provided by the netz98 new media GmbH without any express or 
 * implied warranties. netz98 is under no condition liable for the functional 
 * capability of this software for a certain purpose or the general usability. 
 * netz98 is under no condition liable for any direct or indirect damages resulting 
 * from the use of the software. 
 * Liability and Claims for damages of any kind are excluded. 
 */

/**
 * Overwrites shipping model of magento an inject test for customer groups
 *
 * @category n98
 * @package N98_CustomerGroupCheckout
 */
class N98_CustomerGroupCheckout_Model_Shipping_Shipping
    extends Mage_Shipping_Model_Shipping
{
    /**
     * @var string
     */
    const XML_CUSTOMER_GROUP_CONFIG_FIELD = 'available_for_customer_groups';

    /**
     * @param string $carrierCode
     * @param Varien_Object $request
     * @return N98_CustomerGroupCheckout_Model_Shipping_Shipping
     */
    public function collectCarrierRates($carrierCode, $request)
    {
        if (!$this->_checkCarrierByCustomerGroup($carrierCode)) {
            return $this;
        }
        return parent::collectCarrierRates($carrierCode, $request);
    }

    /**
     * Check if carrier can be used by customer groups
     *
     * @param Mage_Shipping_Model_Carrier_Abstract $carrier
     * @return boolean
     */
    protected function _checkCarrierByCustomerGroup($carrierCode)
    {
        $customer = Mage::helper('customer')->getCustomer();
        /* @var $customer Mage_Customer_Model_Customer */

        $carrierCustomerGroupConfig = Mage::getStoreConfig('carriers/' . $carrierCode . '/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);

        if (!empty($carrierCustomerGroupConfig)) {
            $carrierCustomerGroups = explode(',', $carrierCustomerGroupConfig);
            if (count($carrierCustomerGroups) > 0) {
                if (!in_array($customer->getGroupId(), $carrierCustomerGroups)) {
                    return false;
                }
            }
        }

        // If nothing was specified the shipping carrier is not blocked!
        return true;
    }
}
