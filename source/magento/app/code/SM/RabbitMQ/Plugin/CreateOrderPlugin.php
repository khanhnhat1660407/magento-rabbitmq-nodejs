<?php


namespace SM\RabbitMQ\Plugin;

use Magento\Framework\ObjectManagerInterface;
use SM\RabbitMQ\Model\ResultPublisher;
use SM\DemoOrder\Model\CreateOrderResultFactory;
use SM\RabbitMQ\Model\CreateOrderRequestHandler;
class  CreateOrderPlugin
{
    /**
     * @var ResultPublisher
     */
    protected $resultPublisher;

    /**
     * @var CreateOrderResultFactory
     */
    protected $resultFactory;


    public function __construct(
        ResultPublisher          $resultPublisher,
        CreateOrderResultFactory $resultFactory
    ) {
        $this->resultPublisher = $resultPublisher;
        $this->resultFactory   = $resultFactory;
    }


    /**
     * @param CreateOrderRequestHandler $subject
     * @param $createOrderResult
     */
    public function afterProcess(CreateOrderRequestHandler $subject, $createOrderResult)
    {
        $this->log([
            'plugin' => $createOrderResult
        ]);
        //reply nodejs
        if (!empty($createOrderResult)) {
            try {
                $result = $this->resultFactory->create();
                $result->setError($createOrderResult['error']);
                $result->setMessage($createOrderResult['message']);
                $this->log([
                    'plugin' => $result->getMessage(),
                ]);
                $this->resultPublisher->execute($result);
            } catch (\Exception $e){
                $this->log([
                    'plugin' => $e->getMessage()
                ]);
            }
        }
    }

    public function log($info)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/pushlisher.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($info);
    }
}
