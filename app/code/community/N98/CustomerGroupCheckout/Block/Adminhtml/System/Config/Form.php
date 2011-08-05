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
 * @category n98
 * @package N98_CustomerGroup
 */
class N98_CustomerGroupCheckout_Block_Adminhtml_System_Config_Form
    extends Mage_Adminhtml_Block_System_Config_Form
{
    /**
     * @return Netz98_CustomerGroup_Block_Adminhtml_System_Config_Form
     */
    protected function _initObjects()
    {
        parent::_initObjects();

        $sections = $this->_configFields->getSection($this->getSectionCode(), $this->getWebsiteCode(), $this->getStoreCode());

        /**
         * Create config field during runtime.
         *
         * Check if we are in sales tab and sub-tab payment or shipping.
         * Then we create SimpleXMLElements for form init.
         */
        if ($sections->tab == 'sales' && in_array($sections->label, array('Payment Methods', 'Shipping Methods'))) {
            foreach ($sections->groups as $group) {
                foreach ($group as $subGroup) {
                    if (isset($subGroup->fields)) {
                        $customerGroup = $subGroup->fields->addChild('available_for_customer_groups');
                        $customerGroup->addAttribute('translate', 'label');
                        /* @var $customerGroup Mage_Core_Model_Config_Element */
                        $customerGroup->addChild('label', 'Customer Group');
                        $customerGroup->addChild('frontend_type', 'multiselect');
                        $customerGroup->addChild('source_model', 'adminhtml/system_config_source_customer_group');
                        $customerGroup->addChild('sort_order', 1000);
                        $customerGroup->addChild('show_in_default', 1);
                        $customerGroup->addChild('show_in_website', 1);
                        $customerGroup->addChild('show_in_store', 1);
                    }
                }
            }
        }

        return $this;
    }
}
