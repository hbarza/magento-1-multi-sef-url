<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_MultiSEFURL
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

/**
 * Encrypted config field backend model
 *
 * @category   Codnitive
 * @package    Codnitive_MultiSEFURL
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_MultiSEFURL_Model_System_Config_Backend_Encryptedregcode extends Mage_Core_Model_Config_Data
{

    private $_type = 'Registration Code';

    protected function _afterLoad()
    {
        $value = (string) $this->getValue();
        if (!empty($value) && ($decrypted = Mage::helper('core')->decrypt($value))) {
            $this->setValue($decrypted);
        }
    }

    protected function _beforeSave()
    {
        $value = (string) $this->getValue();
        if (empty($value)) {
            Mage::throwException(Mage::helper('multisefurl')->__('%s is required.', 
                    Mage::helper('multisefurl')->__($this->_type)));
        }
        
        if (preg_match('/^\*+$/', $this->getValue())) {
            $value = $this->getOldValue();
        }
        
        $alnumValidator = new Zend_Validate_Alnum(array('allowWhiteSpace' => false));
        $lenValidator = new Zend_Validate_StringLength(array('min' => 7, 'max' => 15));
        if (!$alnumValidator->isValid($value) || !$lenValidator->isValid($value)) {
            Mage::throwException(Mage::helper('multisefurl')->__('%s is not valid.', 
                    Mage::helper('multisefurl')->__($this->_type)));
        }
        
        if (!empty($value) && ($encrypted = Mage::helper('core')->encrypt($value))) {
            $this->setValue($encrypted);
        }
    }

    public function getOldValue()
    {
        return Mage::helper('core')->decrypt(parent::getOldValue());
    }

}
