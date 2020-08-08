<?php

namespace SM\DemoOrder\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Data\Form\FormKey;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Customer\Model\CustomerFactory;
use Magento\Sales\Model\Service\OrderService;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\Order as OrderModel;
use SM\DemoOrder\Api\Data\OrderInterface;

class Order extends AbstractHelper
{

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var FormKey
     */
    protected $formkey;
    /**
     * @var QuoteFactory
     */
    protected $quote;
    /**
     * @var QuoteManagement
     */
    protected $quoteManagement;
    /**
     * @var CustomerFactory
     */
    protected $customerFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * CreateOrder constructor.
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Product $product
     * @param FormKey $formkey
     * @param QuoteFactory $quote
     * @param QuoteManagement $quoteManagement
     * @param CustomerFactory $customerFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param OrderService $orderService
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Product $product,
        FormKey $formkey,
        QuoteFactory $quote,
        QuoteManagement $quoteManagement,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        OrderService $orderService
    )
    {
        $this->storeManager = $storeManager;
        $this->product = $product;
        $this->formkey = $formkey;
        $this->quote = $quote;
        $this->quoteManagement = $quoteManagement;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->orderService = $orderService;
        parent::__construct($context);
    }

    /**
     * @param OrderInterface | null $orderData
     * @return array
     */
    public function createNewOrder($orderData = null)
    {
        if($orderData) {
            $nodeJsOrderId = $orderData->getOrderId();
            try {
                $store = $this->storeManager->getStore();
                $websiteId = $this->storeManager->getStore()->getWebsiteId();
                $customer = $this->customerFactory->create();
                $customer->setWebsiteId($websiteId);
                $orderAddress = $orderData->getShippingAddress();
                $customer->loadByEmail($orderData->getEmail());
                if (!$customer->getEntityId()) {
                    $customer->setWebsiteId($websiteId)
                        ->setStore($store)
                        ->setFirstname($orderAddress->getFirstname())
                        ->setLastname($orderAddress->getLastname())
                        ->setEmail($orderData->getEmail())
                        ->setPassword($orderData->getEmail());
                    $customer->save();
                }
                $quote = $this->quote->create();
                $quote->setStore($store);
                $customer = $this->customerRepository->getById($customer->getEntityId());
                $quote->setCurrency();
                $quote->assignCustomer($customer);

                foreach ($orderData->getItems() as $item) {
                    $product = $this->product->load($item->getProductId());
                    //Check exists, quantity
                    $product->setPrice($product->getFinalPrice());
                    $quote->addProduct(
                        $product,
                        intval($item['qty'])
                    );
                }

                $quote->getBillingAddress()->addData($orderAddress->toArray());
                $quote->getShippingAddress()->addData($orderAddress->toArray());

                $shippingAddress = $quote->getShippingAddress();
                $shippingAddress->setCollectShippingRates(true)
                    ->collectShippingRates()
                    ->setShippingMethod('flatrate_flatrate');
                $quote->setPaymentMethod('checkmo');
                $quote->setInventoryProcessed(false);
                $quote->save();

                $quote->getPayment()->importData(['method' => 'checkmo']);
                $quote->collectTotals()->save();

                $order = $this->quoteManagement->submit($quote);
                $order->setState(OrderModel::STATE_PROCESSING)->setStatus(OrderModel::STATE_PROCESSING);
                $order->setEmailSent(0);
                $order->setNodeOrderId($nodeJsOrderId);
                $order->save();
                if ($order->getEntityId()) {
                    $orderIncrementId = $order->getRealOrderId();
                    $orderStatus = $order->getStatus();
                    sleep(5);
                    return [
                        'error'   => false,
                        'message' => "{$nodeJsOrderId}|{$orderIncrementId}|{$orderStatus}"
                    ];
                }
            } catch (\Exception $e) {
                $this->log('ERROR: '. $e->getMessage());
                sleep(5);
                return [
                    'error'   => true,
                    'message' => "{$nodeJsOrderId}|{$e->getMessage()}"
                ];
            }
        }
        return [];
    }

    /**
     * @param $info
     */
    public function log($info)
    {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/create_order_' . date("Y-m-d") . '.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($info);
    }
}

