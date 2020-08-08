<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SM\DemoOrder\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface as ModuleDataSetupInterfaceAlias;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetup;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

/**
 * Class AddNodejsOrderId
 * @package SM\DemoOrder\Setup\Patch
 */
class AddNodeOrderId implements DataPatchInterface, PatchVersionInterface
{
    const NODE_ORDER_ID = 'node_order_id';
    /**
     * @var ModuleDataSetupInterfaceAlias
     */
    private $moduleDataSetup;

    /**
     * @var QuoteSetupFactory
     */
    private $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * InitQuoteAndOrderAttributes constructor.
     * @param ModuleDataSetupInterfaceAlias $moduleDataSetup
     * @param QuoteSetupFactory $quoteSetupFactory
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterfaceAlias $moduleDataSetup,
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $salesSetup->addAttribute('order', self::NODE_ORDER_ID, ['type' => 'text']);
        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
