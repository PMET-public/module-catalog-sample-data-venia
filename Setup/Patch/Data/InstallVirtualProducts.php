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
    private $moduleDataSetup;

    /**
     * InstallSimpleProducts constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Category $categorySetup
     * @param Product $productSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup, Category $categorySetup,
                                 Product $productSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetup = $categorySetup;
        $this->productSetup = $productSetup;
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
        return [

        ];
    }

}
