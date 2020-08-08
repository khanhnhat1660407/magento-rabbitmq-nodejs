<?php
namespace SM\DemoOrder\Api\Data;

use SM\DemoOrder\Api\Data\OrderItemInterface;
use SM\DemoOrder\Api\Data\ShippingAddressInterface;
interface OrderInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const ORDER_ID = 'order_id';

    const CURRENCY_ID = 'currency_id';

    const EMAIL = 'email';

    const ITEMS = 'items';

    const SHIPPING_ADDRESS = 'shipping_address';

    const ATTRIBUTES = [
        self::ORDER_ID,
        self::CURRENCY_ID,
        self::EMAIL,
        self::ITEMS,
        self::SHIPPING_ADDRESS,
    ];

    /**
     * Nodejs Order Id
     * @return string
     */
    public function getOrderId();

    /**
     * Get Nodejs Order Id
     * @param string $orderId
     * @return $this
     */
    public function setOrderId($orderId);


    /**
     * Currency Id
     * @return string
     */
    public function getCurrencyId();

    /**
     * Get Currency Id
     * @param string $currencyId
     * @return $this
     */
    public function setCurrencyId($currencyId);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     *
     * @return \SM\DemoOrder\Api\Data\OrderItemInterface[]
     */
    public function getItems();

    /**
     * @param \SM\DemoOrder\Api\Data\OrderItemInterface[] $items
     * @return $this
     */
    public function setItems($items);

    /**
     * @return \SM\DemoOrder\Api\Data\ShippingAddressInterface
     */
    public function getShippingAddress();

    /**
     * @param \SM\DemoOrder\Api\Data\ShippingAddressInterface $shippingAddress
     * @return $this
     */
    public function setShippingAddress($shippingAddress);
}

