<?php
namespace SM\DemoOrder\Model;

use Magento\Framework\Api\AttributeValueFactory;
use SM\DemoOrder\Api\Data\CreateOrderResultInterface as ResultInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class CreateOrderResult extends AbstractExtensibleModel implements ResultInterface
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
    public function getError()
    {
        return $this->getData(ResultInterface::ERROR);
    }

    /**
     * @inheritDoc
     */
    public function setError($error)
    {
        return $this->setData(ResultInterface::ERROR, $error);
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->getData(ResultInterface::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        return $this->setData(ResultInterface::MESSAGE, $message);
    }
}
