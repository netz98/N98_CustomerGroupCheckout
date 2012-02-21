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
 * Observer to limit access to customer groups by customer group
 *
 * @category n98
 * @package Netz98_CustomerGroup
 */
class N98_CustomerGroupCheckout_Model_Payment_Observer
{
    /**
     * @var string
     */
    const XML_CUSTOMER_GROUP_CONFIG_FIELD = 'available_for_customer_groups';

    /**
     * Check if customer group can use the payment method
     *
     * @param Varien_Event_Observer $observer
     * @return bool
     */
    public function methodIsAvailable(Varien_Event_Observer $observer)
    {
        $paymentMethodInstance = $observer->getMethodInstance();
        /* @var $paymentMethodInstance Mage_Payment_Model_Method_Abstract */
        $result = $observer->getResult();

        $customer = Mage::helper('customer')->getCustomer();
        /* @var $customer Mage_Customer_Model_Customer */

        if ($paymentMethodInstance instanceof Mage_Paypal_Model_Standard) {
            $customerGroupConfig = Mage::getStoreConfig('paypal/wps/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } elseif ($paymentMethodInstance instanceof Mage_Paypal_Model_Express) {
            $customerGroupConfig = Mage::getStoreConfig('paypal/express/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } elseif ($paymentMethodInstance instanceof Mage_GoogleCheckout_Model_Payment) {
            $customerGroupConfig = Mage::getStoreConfig('google/checkout/' . self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        } else {
            $customerGroupConfig = $paymentMethodInstance->getConfigData(self::XML_CUSTOMER_GROUP_CONFIG_FIELD);
        }
        if (!empty($customerGroupConfig)) {
            $methodCustomerGroups = explode(',', $customerGroupConfig);
            if (count($methodCustomerGroups) > 0) {
                if (!in_array($customer->getGroupId(), $methodCustomerGroups)) {
                    $result->isAvailable = false;
                }
            }
        }
        return true;
    }
}
