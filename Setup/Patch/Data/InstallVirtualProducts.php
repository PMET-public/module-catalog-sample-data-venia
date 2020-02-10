<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\CatalogSampleDataVenia\Setup\Category;
use Magento\CatalogSampleDataVenia\Model\Virtual\Product;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;


class InstallVirtualProducts implements DataPatchInterface
{
    /**
     * Setup class for category
     *
     * @var Category
     */
    protected $categorySetup;


    /**
     * Setup class for products
     *
     * @var Product
     */
    protected $productSetup;


    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */

   /** @var StoreManagerInterface  */
    protected $storeManager;

    /**
     * InstallVirtualProducts constructor.
     * @param Product $productSetup
     * @param Category $categorySetup
     * @param StoreManagerInterface|null $storeManager
     */
    public function __construct(
                                 Product $productSetup, Category $categorySetup,StoreManagerInterface $storeManager = null)
    {
        $this->categorySetup = $categorySetup;
        $this->productSetup = $productSetup;
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
    }

    public function apply()
    {
        $this->categorySetup->install(['Magento_CatalogSampleDataVenia::fixtures/Virtual/categories.csv']);
        $this->productSetup->install(
            [
                'Magento_CatalogSampleDataVenia::fixtures/Virtual/products_virtual.csv'
            ],
            [
                'Magento_CatalogSampleDataVenia::fixtures/Virtual/images_virtual.csv'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

}
