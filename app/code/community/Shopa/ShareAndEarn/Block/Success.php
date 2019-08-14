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
        $orderEmail = $this->getOrderEmail();
        $apiKey = $this->getApiKey();
        $apiSecret = Mage::getStoreConfig('shopa_shareandearn_options/security/api_secret');

        $data = "$commission:$price:$currency:$orderId:$orderEmail:$apiKey:$apiSecret";
        return sha1($data);
    }

    public function getApiKey() {
        return Mage::getStoreConfig('shopa_shareandearn_options/security/api_key');
    }

    public function getOrderId() {
        return Mage::getSingleton('checkout/session')->getLastOrderId();
    }

    public function getOrderEmail()
    {
        if ($this->getOrderId()) {
            return $this->getOrder()->getCustomerEmail();
        } else {
            return '';
        }
    }

    public function getPrice() {
        return $this->getSubtotalInclTax();
    }

    public function getCurrency() {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    public function getOrder() {
      $orderId = $this->getOrderId();
      return Mage::getModel('sales/order')->load($orderId);
    }

    public function getSubtotalInclTax() {;
        if ($this->getOrderId()) {
            return $this->getOrder()->getSubtotalInclTax();
        } else {
            return 0.0;
        }
    }

    public function getProductUrls()
    {
        if (!$this->getOrderId()) {
          return array();
        } else {
            $order = $this->getOrder();

            $productIds = array();
            foreach ($order->getAllItems() as $item) {
                $productIds[] = $item->getProductId();
            }

            $productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addIdFilter($productIds)
                ->load();

            $productUrls = array();
            foreach ($productCollection as $product) {
                $productUrls[] = $product->getProductUrl();
            }
            return $productUrls;
        }
    }

    public function getButtonUrl() {
        return Mage::getStoreConfig('shopa_shareandearn_options/advanced/shopa_base_url') . "btn.js";
    }
}
