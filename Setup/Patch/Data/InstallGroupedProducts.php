<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;


use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\CatalogSampleDataVenia\Model\Grouped\Product as GroupProduct;

class InstallGroupedProducts implements DataPatchInterface
{


    /**
     * Setup class for group products
     *
     * @var GroupProduct
     */
    protected $groupedProduct;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * InstallGroupedProducts constructor.
     * @param GroupProduct $groupedProduct
     * @param StoreManagerInterface|null $storeManager
     */
    public function __construct(
        GroupProduct $groupedProduct,
        StoreManagerInterface $storeManager = null
    ) {
        $this->groupedProduct = $groupedProduct;
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
    }

    public function apply()
    {
        $this->groupedProduct->install(
            ['Magento_CatalogSampleDataVenia::fixtures/Grouped/jewelry_grouped.csv'] ,
            ['Magento_CatalogSampleDataVenia::fixtures/Grouped/images_jewelry_grouped.csv']
        );
    }

    public static function getDependencies()
    {
        return [InstallSimpleProducts::class,InstallVirtualProducts::class];
    }

    public function getAliases()
    {
        return [];
    }
}
