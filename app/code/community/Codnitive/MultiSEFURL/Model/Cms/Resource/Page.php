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
 * Cms page mysql resource
 *
 * @category   Codnitive
 * @package    Codnitive_MultiSEFURL
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_MultiSEFURL_Model_Cms_Resource_Page extends Mage_Cms_Model_Resource_Page
{
    
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $isActive = Mage::getModel('multisefurl/config')->isActive();
        if ($isActive) {
            $identifier = Mage::helper('multisefurl')
                    ->getValidUrl($object->getData('identifier'));
            $object->setData('identifier', $identifier);
        }

        return parent::_beforeSave($object);
    }
    
    protected function isValidPageIdentifier(Mage_Core_Model_Abstract $object)
    {
        $isActive = Mage::getModel('multisefurl/config')->isActive();
        if ($isActive) {
            return true;
        }
        
        return parent::isValidPageIdentifier($object);
    }
    
}
