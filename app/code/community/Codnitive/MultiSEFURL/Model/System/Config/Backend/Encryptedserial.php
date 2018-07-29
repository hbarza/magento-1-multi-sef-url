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
class Codnitive_MultiSEFURL_Model_System_Config_Backend_Encryptedserial extends Mage_Core_Model_Config_Data
{

    private $_type = 'Serial Number';
    
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
        
        $pattern = '/^[a-zA-Z0-9]{32}\:[A-Z]{2}\d{4}[A-Z]{3}\d{4}$/';
        $validator = new Zend_Validate_Regex(array('pattern' => $pattern));
        if (!$validator->isValid($value)) {
            Mage::throwException(Mage::helper('multisefurl')->__('%s is not valid.', 
                    Mage::helper('multisefurl')->__($this->_type)));
        }
        
        try {
            $crypt = Varien_Crypt::factory()->init('3ee2a23ba72ce85081fae961d2e51b1b');
            $inf = array(
                'sn' => base64_encode($crypt->encrypt((string)$value)),
                'bu' => base64_encode($crypt->encrypt((string)Mage::getStoreConfig('web/unsecure/base_url'))),
            );

            try {
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, Mage::helper('multisefurl')->getConUrl().'update/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $inf);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $data = curl_exec($ch);
                curl_close($ch);
            }
            catch (Exception $e) {
                $data = false;
            }
            
            if (false == $data || '1' !== $data) {
                Mage::throwException(Mage::helper('multisefurl')->__('%s is not valid.', 
                    Mage::helper('multisefurl')->__($this->_type)));
            }
        }
        catch (Exception $e) {
            Mage::throwException($e->getMessage());
            return $this;
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
