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

class Codnitive_MultiSEFURL_Helper_Data extends Mage_Core_Helper_Data
{
    
    private $_url = 'http://extension.codnitive.com/status/';
    
    protected $_urlInvalidCharsPattern = array(
        '#\s#', '#\(|\)#', '#\*|\!|\~#', '#\_|\=|\+#', '#\|#', '#\/#', '#\<|\>#',
        '#\,|\;|\:#', '/#/', '#\?|\&#',
		'#،|؛#', '#»|«#',
    );
    
    public function __construct()
    {
        if (time() > (int)$this->getLastCheck() + (int)$this->getFrq()) {
            $this->setLastCheck();
        }
    }

    public function getUrlInvalidCharacters()
    {
        return $this->_urlInvalidCharsPattern;
    }
    
    public function getValidUrl($str)
    {
        $url = preg_replace($this->getUrlInvalidCharacters(), '-', $str);
        $url = preg_replace('/(\-)+/', '-', $url);
        $url = mb_strtolower($url,'UTF-8');
        $url = trim($url, '-');
        
        return $url;
    }
    
    public function getFrq()
    {
        return Mage::getStoreConfig(Codnitive_MultiSEFURL_Model_Config::getNamespace() . 'chkfrq');
    }

    public function getLastCheck()
    {
        $namespace = Codnitive_MultiSEFURL_Model_Config::EXTENSION_NAMESPACE;
        return Mage::app()->loadCache('codnitive_'.$namespace.'_lastcheck');
    }

    public function setLastCheck()
    {
        $namespace = Codnitive_MultiSEFURL_Model_Config::EXTENSION_NAMESPACE;
        Mage::app()->saveCache(time(), 'codnitive_'.$namespace.'_lastcheck');
        return $this;
    }
    
    public function getConUrl()
    {
        return $this->_url;
    }

}
