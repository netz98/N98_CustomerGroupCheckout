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
