<?php
namespace SM\DemoOrder\Api\Data;

interface OrderItemInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const PRODUCT_ID = 'product_id';

    const QTY = 'qty';

    const ATTRIBUTES = [
        self::PRODUCT_ID,
        self::QTY,
    ];


    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return int
     */
    public function getQty();

    /**
     * @param int $qty
     * @return $this
     */
    public function setQty($qty);
}
