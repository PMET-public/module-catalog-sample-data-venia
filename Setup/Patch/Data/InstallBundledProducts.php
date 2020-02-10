<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;


use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Magento\CatalogSampleDataVenia\Model\Bundle\Product as BundleProduct;

class InstallBundledProducts implements DataPatchInterface
{


    /**
     * Setup class for bundle products
     *
     * @var BundleProduct
     */
    protected $bundleProduct;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param BundleProduct $bundleProduct
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        BundleProduct $bundleProduct,
        StoreManagerInterface $storeManager = null
    ) {
        $this->bundleProduct = $bundleProduct;
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
    }

    public function apply()
    {
        $this->bundleProduct->install(
            ['Magento_CatalogSampleDataVenia::fixtures/Bundle/jewelry_bundle.csv'] ,
            ['Magento_CatalogSampleDataVenia::fixtures/Bundle/images_jewelry_bundle.csv']
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
