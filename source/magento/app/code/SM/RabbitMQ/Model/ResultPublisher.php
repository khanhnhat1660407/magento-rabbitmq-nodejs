<?php
namespace SM\RabbitMQ\Model;

use Magento\Framework\MessageQueue\PublisherInterface;
use SM\DemoOrder\Api\Data\CreateOrderResultInterface;

class ResultPublisher
{
    const TOPIC_CREATE_ORDER_RESULT = 'create.order.result';
    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * ResultPublisher constructor.
     * @param PublisherInterface $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @param $result
     * @return mixed|null
     */
    public function execute($result)
    {
        $this->log($result->getMessage());
        return $this->publisher->publish(self::TOPIC_CREATE_ORDER_RESULT, $result);
    }


    /**
     * @param $info
     */
    public function log($info)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/pushlisher.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($info);
    }
}
