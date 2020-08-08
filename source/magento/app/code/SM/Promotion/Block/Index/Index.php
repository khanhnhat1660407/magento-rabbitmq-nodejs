<?php
namespace SM\Promotion\Block\Index;
use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\BlockFactory;
class Index extends Template
{
    /**
     * @var BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var Curl
     */

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        BlockFactory $blockFactory,
        array $data = []
    )
    {
        $this->_storeManager = $storeManager;
        $this->_blockFactory = $blockFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getApiBaseUrl()
    {
//        return $this->_storeManager->getStore()->getUrl('index.php');
        return 'http://nginx/index.php';
    }

    /**
     * @param $identifier
     */
    public function getBlockIdByIdentifier($identifier)
    {
        $cmsBlock = $this->_blockFactory->create()->setStoreId(0)->load($identifier, 'identifier');
        return $cmsBlock->getId();
    }

}
