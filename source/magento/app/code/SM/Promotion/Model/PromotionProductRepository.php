<?php
namespace SM\Promotion\Model;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use SM\Promotion\Api\PromotionProductRepositoryInterface;
use Zend_Db_Expr;
use Magento\Catalog\Block\Product\Context as ProductContext;
use Magento\Store\Model\App\Emulation;
use SM\Promotion\Api\Data\PromotionProductInterfaceFactory;
use Magento\Framework\App\ObjectManager;
/**
 * Class PromotionProduct
 * @package SM\Promotion\Model
 */
class PromotionProductRepository implements PromotionProductRepositoryInterface
{

    /**
     * @var ProductCollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var TimezoneInterface
     */
    protected $_localeDate;


    /**
     * @var \Magento\Catalog\Block\Product\ImageBuilder
     */
    protected $_imageBuilder;

    /**
     * @var Emulation
     */
    protected $_appEmulation;

    /**
     * @var PromotionProductInterfaceFactory
     */
    protected $_promotionProductFactory;
    /**
     * PromotionProduct constructor.
     * @param ProductCollectionFactory $productCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $localeDate
     */
    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        TimezoneInterface $localeDate,
        ProductContext $productContext,
        Emulation $appEmulation,
        PromotionProductInterfaceFactory $promotionProductFactory = null
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_storeManager = $storeManager;
        $this->_localeDate = $localeDate;
        $this->_imageBuilder = $productContext->getImageBuilder();
        $this->_appEmulation = $appEmulation;
        $this->_promotionProductFactory = $promotionProductFactory ?: ObjectManager::getInstance()
            ->get(\SM\Promotion\Api\Data\PromotionProductInterfaceFactory::class);
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @param int $limitProduct
     * @return \SM\Promotion\Api\Data\PromotionProductInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList($limitProduct)
    {
        $now =  $this->_localeDate->date()->setTime(0, 0);
        $storeId = $this->getStoreId();
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect([
            'entity_id',
            'sku',
            'name',
            'price',
            'special_price',
            'is_saleable',
            'small_image',
            'store_id',
            'special_from_date',
            'special_to_date',
        ]);
        $collection->addAttributeToFilter('special_price', [
            ['notnull' => true],
            ['eq' => 0]
        ]);
        $collection->addAttributeToFilter('price', ['gt' => new Zend_Db_Expr('at_special_price.value')]);
        $collection->addAttributeToFilter(
            'special_from_date',
            ['lteq' => $now->format('Y-m-d H:i:s')]
        );
        $collection->addAttributeToFilter([
            ['attribute' => 'special_to_date','null' => true ],
            ['attribute' => 'special_to_date','gteq' => $now->format('Y-m-d H:i:s') ],
        ]);
        $collection->addStoreFilter($storeId);
        $collection->setPageSize($limitProduct);

        $promotionProductList = [];
        foreach ($collection as $product) {
            $imgUrl = $this->getImage($product,'category_page_grid');
            $product->setSmallImage($imgUrl);
            /** @var \SM\Promotion\Api\Data\PromotionProductInterface $promotionProduct */
            $promotionProduct = $this->_promotionProductFactory->create();
            $promotionProduct->setId($product['entity_id'])
                ->setSku($product['sku'])
                ->setName($product['name'])
                ->setPrice($product['price'])
                ->setSpecialPrice($product['special_price'])
                ->setIsSaleable($product['is_saleable'])
                ->setSmallImage($product['small_image'])
                ->setStoreId($product['store_id'])
                ->setSpecialFromDate($product['special_from_date'])
                ->setSpecialToDate($product['special_to_date']);

            $promotionProductList[] = $promotionProduct;
        }


        return $promotionProductList;
    }

    /**
     * @param $product
     * @param $imageId
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImage($product, $imageId)
    {
        $storeId = $this->getStoreId();
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $helperImport   = $objectManager->get('\Magento\Catalog\Helper\Image');

        $this->_appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);

        $imageUrl = $helperImport
            ->init($product, $imageId)
            ->setImageFile($product->getFile())
            ->getUrl();
        $this->_appEmulation->stopEnvironmentEmulation();
        return $imageUrl;
    }




}