<?php
/***
 * Copyright (c) 2011 netz98 new media GmbH
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 
 * Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 * 
 * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 * 
 * Neither the name of the project's author nor the names of its
 * contributors may be used to endorse or promote products derived from
 * this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
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
     * @return Netz98_CustomerGroupCheckout_Model_Shipping_Shipping
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
                return in_array($customer->getGroupId(), $carrierCustomerGroups);
            }
        }

        // If nothing was specified the shipping carrier is not blocked!
        return true;
    }
}
