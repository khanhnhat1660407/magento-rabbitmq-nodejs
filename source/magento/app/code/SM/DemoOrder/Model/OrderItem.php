<?php
namespace SM\DemoOrder\Model;

use Magento\Framework\Api\AttributeValueFactory;
use SM\DemoOrder\Api\Data\OrderItemInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class OrderItem extends AbstractExtensibleModel implements OrderItemInterface
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
    public function getProductId()
    {
        return $this->getData(OrderItemInterface::PRODUCT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setProductId($productId)
    {
        return $this->setData(OrderItemInterface::PRODUCT_ID, $productId);
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->getData(OrderItemInterface::QTY);
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        return $this->setData(OrderItemInterface::QTY, $qty);

    }
}
