
<?php
/**
 * Shopa Button
 *
 * @author Shopa
 */
class Shopa_ShareAndEarn_Block_Success extends Mage_Core_Block_Template
{
    public function getCommission() {
        return Mage::getStoreConfig('shopa_shareandearn_options/commission/percentage') . '%';
    }

    public function getSignature() {
        $commission = $this->getCommission();
        $price = $this->getPrice();
        $currency = $this->getCurrency();
        $orderId = $this->getOrderId();
        $apiKey = $this->getApiKey();
        $apiSecret = Mage::getStoreConfig('shopa_shareandearn_options/security/api_secret');

        $data = "$commission:$price:$currency:$orderId:$apiKey:$apiSecret";
        return sha1($data);
    }

    public function getApiKey() {
        return Mage::getStoreConfig('shopa_shareandearn_options/security/api_key');
    }

    public function getOrderId() {
        return Mage::getSingleton('checkout/session')->getLastOrderId();
    }

    public function getPrice() {
        return $this->getSubtotalInclTax();
    }

    public function getCurrency() {
        return 'GBP';
    }

    public function getSubtotalInclTax() {
        $orderId = $this->getOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            return $order->getSubtotalInclTax();
        } else {
            return 0.0;
        }
    }

    public function getButtonUrl() {
        return Mage::getStoreConfig('shopa_shareandearn_options/advanced/shopa_base_url') . "btn.js";
    }
}
