<?php

namespace SM\Promotion\Api;

/**
 * PromotionProductRepositoryInterface
 * @package SM\Promotion\Api
 */
interface PromotionProductRepositoryInterface
{

    /**
     * Get promotion products
     *
     * @param int $limitProduct
     * @return \SM\Promotion\Api\Data\PromotionProductInterface[]
     */
    public function getList($limitProduct);
}


