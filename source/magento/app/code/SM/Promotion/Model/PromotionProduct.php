<?php
namespace SM\Promotion\Model;

use Magento\Catalog\Model\AbstractModel;
use SM\Promotion\Api\Data\PromotionProductInterface;

class PromotionProduct extends AbstractModel implements PromotionProductInterface
{

    /**
     * Product id
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->_getData(self::ID);
    }

    /**
     * Set product id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Product name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->_getData(self::NAME);
    }

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Product price
     *
     * @return float|null
     */
    public function getPrice()
    {
        return $this->_getData(self::PRICE);
    }

    /**
     * Set product price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        return $this->setData(self::PRICE, $price);
    }

    /**
     * Product special price
     *
     * @return float|null
     */
    public function getSpecialPrice()
    {
        return $this->_getData(self::SPECIAL_PRICE);
    }

    /**
     * Set product special price
     *
     * @param float $price
     * @return $this
     */
    public function setSpecialPrice($price)
    {
        return $this->setData(self::SPECIAL_PRICE, $price);
    }

    /**
     * get is saleable
     *
     * @return int
     */
    public function getIsSaleable()
    {
        return $this->_getData(self::IS_SALEABLE);
    }

    /**
     * set is saleable
     *
     * @param int $isSaleable
     * @return $this
     */
    public function setIsSaleable($isSaleable)
    {
        return $this->setData(self::IS_SALEABLE, $isSaleable);
    }

    /**
     * Product small image path
     *
     * @return string
     */
    public function getSmallImage()
    {
        return $this->_getData(self::SMALL_IMAGE);
    }

    /**
     * Set product small image
     *
     * @param string $smallImage
     * @return $this
     */
    public function setSmallImage($smallImage)
    {
        return $this->setData(self::SMALL_IMAGE, $smallImage);
    }

    /**
     * Product store id
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_getData(self::STORE_ID);
    }

    /**
     * Set product store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Product special from date
     *
     * @return string|null
     */
    public function getSpecialFromDate()
    {
        return $this->_getData(self::SPECIAL_FROM_DATE);
    }

    /**
     * set Special From Date
     *
     * @param string $specialFromDate
     * @return $this
     */
    public function setSpecialFromDate($specialFromDate)
    {
        return $this->setData(self::SPECIAL_FROM_DATE, $specialFromDate);
    }

    /**
     * Product special to date
     *
     * @return string|null
     */
    public function getSpecialToDate()
    {
        return $this->_getData(self::SPECIAL_TO_DATE);

    }

    /**
     * set Special To Date
     * @param string $specialToDate
     * @return $this
     */
    public function setSpecialToDate($specialToDate)
    {
        return $this->setData(self::SPECIAL_TO_DATE, $specialToDate);
    }
}
