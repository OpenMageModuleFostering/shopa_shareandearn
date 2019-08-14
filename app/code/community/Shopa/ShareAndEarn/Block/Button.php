<?php
/**
 * Shopa Button
 *
 * @author Shopa
 */
class Shopa_ShareAndEarn_Block_Button extends Mage_Catalog_Block_Product_Abstract
{
    public function getCommission() {
        return Mage::getStoreConfig('shopa_shareandearn_options/commission/percentage') . '%';
    }

    public function getPrice() {
        return $this->getPriceInclTax();
    }

    public function getCurrency() {
        return 'GBP';
    }

    public function getIsButtonVisible() {
        $show_button = Mage::getStoreConfig('shopa_shareandearn_options/commission/show_button');
        return $show_button;
    }

    public function getApiKey() {
        return Mage::getStoreConfig('shopa_shareandearn_options/security/api_key');
    }

    public function getSignature() {
        $commission = $this->getCommission();
        $price = $this->getPrice();
        $currency = $this->getCurrency();
        $apiKey = $this->getApiKey();
        $apiSecret = Mage::getStoreConfig('shopa_shareandearn_options/security/api_secret');

        $data = "$commission:$price:$currency:$apiKey:$apiSecret";
        return sha1($data);
    }

    public function getPriceInclTax() {
        $product = $this->getProduct();
        return $this->helper('tax')->getPrice($product, $product->getFinalPrice(), true);
    }

    public function getButtonUrl() {
        return Mage::getStoreConfig('shopa_shareandearn_options/advanced/shopa_base_url') . "btn.js";
    }
}
