<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace SM\RabbitMQ\Model;
use SM\DemoOrder\Helper\Order as OrderHelper;
use SM\DemoOrder\Api\Data\OrderInterface;
class CreateOrderRequestHandler
{

    /**
     * @var OrderHelper
     */
    protected $orderHelper;

    /**
     * RabbitMQRequestHandler constructor.
     * @param OrderHelper $orderHelper
     */
    public function __construct(OrderHelper $orderHelper)
    {
        $this->orderHelper = $orderHelper;
    }

    /**
     * @param OrderInterface $orderData
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function process($orderData)
    {
        return $this->orderHelper->createNewOrder($orderData);
    }

    /**
     * @param $info
     */
    public function log($info)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/create_order.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($info);
    }
}
