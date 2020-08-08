<?php
namespace SM\DemoOrder\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Sales\Api\Data\OrderAddressInterface;
use SM\DemoOrder\Api\Data\OrderInterface;
use SM\DemoOrder\Api\Data\OrderItemInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
class Order extends AbstractExtensibleModel implements OrderInterface
{

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }


    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(OrderInterface::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(OrderInterface::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getCurrencyId()
    {
        return $this->getData(OrderInterface::CURRENCY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setCurrencyId($currencyId)
    {
        return $this->setData(OrderInterface::CURRENCY_ID, $currencyId);
    }

    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->getData(OrderInterface::EMAIL);
    }

    /**
     * @inheritDoc
     */
    public function setEmail($email)
    {
        return $this->setData(OrderInterface::EMAIL, $email);
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return $this->getData(OrderInterface::ITEMS);
    }

    /**
     * @inheritDoc
     */
    public function setItems($items)
    {
        return $this->setData(OrderInterface::ITEMS, $items);
    }

    /**
     * @inheritDoc
     */
    public function getShippingAddress()
    {
        return $this->getData(OrderInterface::SHIPPING_ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function setShippingAddress($shippingAddress)
    {
        return $this->setData(OrderInterface::SHIPPING_ADDRESS, $shippingAddress);
    }

    public function log($info)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/create_order.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($info);
    }
}
