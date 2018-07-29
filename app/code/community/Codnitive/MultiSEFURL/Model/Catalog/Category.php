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

class Codnitive_MultiSEFURL_Model_Catalog_Category extends Mage_Catalog_Model_Category
{
    
    public function formatUrlKey($str)
    {
        $isActive = Mage::getModel('multisefurl/config')->isActive();
        if (!$isActive) {
            return parent::formatUrlKey($str);
        }
        
        $urlKey = Mage::helper('multisefurl')->getValidUrl($str);
        return $urlKey;
    }
  
}
