<?php

namespace SM\Promotion\Setup;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $_blockFactory;

    /**
     * UpgradeData constructor.
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        BlockFactory $blockFactory
    ) {
        $this->_blockFactory = $blockFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

            $cmsBlockData = [
                'title' => 'Promotions',
                'identifier' => 'promotion_cms_block',
                'content' => '<div class="logo-swe">
                                 <a class="logo-swe-link" href="/" title="">
                                     <img class="logo-swe-img" src="https://file.hstatic.net/1000344185/file/uoemj1xih2cluupbe1vw-kvjkaw1umbarw_b4167849b7b34a4687658886e20ddd0e.gif">
                                 </a>
                              </div>',
                'is_active' => 1,
                'stores' => [0],
                'sort_order' => 0
            ];

            $this->_blockFactory->create()->setData($cmsBlockData)->save();
            $setup->endSetup();
    }
}