<?php

namespace SM\Promotion\Api\Data;


/**
 * @api
 * @since 100.0.2
 */
interface PromotionProductInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */

    const ID = 'entity_id';

    const SKU = 'sku';

    const NAME = 'name';

    const PRICE = 'price';

    const SPECIAL_PRICE = 'special_price';

    const IS_SALEABLE = 'is_salable';

    const SMALL_IMAGE = 'small_image';

    const STORE_ID = 'store_id';

    const SPECIAL_FROM_DATE = 'special_from_date';

    const SPECIAL_TO_DATE = 'special_to_date';

    const ATTRIBUTES = [
        self::ID,
        self::NAME,
        self::PRICE,
        self::SPECIAL_PRICE,
        self::IS_SALEABLE,
        self::SMALL_IMAGE,
        self::STORE_ID,
        self::SPECIAL_FROM_DATE,
        self::SPECIAL_TO_DATE,
    ];
    /**#@-*/

    /**
     * Product id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set product id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);


    /**
     * Product name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Product price
     *
     * @return float|null
     */
    public function getPrice();

    /**
     * Set product price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Product special price
     *
     * @return float|null
     */
    public function getSpecialPrice();

    /**
     * Set product special price
     *
     * @param float $price
     * @return $this
     */
    public function setSpecialPrice($price);

    /**
     * get is saleable
     *
     * @return int
     */
    public function getIsSaleable();

    /**
     * set is saleable
     *
     * @param int $isSaleable
     * @return $this
     */
    public function setIsSaleable($isSaleable);

    /**
     * Product small image path
     *
     * @return string
     */
    public function getSmallImage();

    /**
     * Set product small image
     *
     * @param string $smallImage
     * @return $this
     */
    public function setSmallImage($smallImage);

    /**
     * Product store id
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set product store id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Product special from date
     *
     * @return string|null
     */
    public function getSpecialFromDate();

    /**
     * set Special From Date
     *
     * @param string $specialFromDate
     * @return $this
     */
    public function setSpecialFromDate($specialFromDate);

    /**
     * Product special to date
     *
     * @return string|null
     */
    public function getSpecialToDate();


    /**
     * set Special To Date
     * @param string $specialToDate
     * @return $this
     */
    public function setSpecialToDate($specialToDate);

}
