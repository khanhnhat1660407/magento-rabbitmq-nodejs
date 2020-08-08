<?php


namespace SM\DemoOrder\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Sales\Api\OrderRepositoryInterface;

class NodeOrderId extends Column
{
    /**
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;

    public function __construct(
        OrderRepositoryInterface $_orderRepository,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_orderRepository = $_orderRepository;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $order = $this->_orderRepository->get($item['entity_id']);
                $nodeOrderId = $order->getData('node_order_id');
                $item[$this->getData('name')] = $nodeOrderId;
            }
        }
        return $dataSource;
    }
}
