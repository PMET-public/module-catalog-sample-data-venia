<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\CatalogSampleDataVenia\Setup\Category;
use Magento\CatalogSampleDataVenia\Setup\Attribute;
use Magento\CatalogSampleDataVenia\Setup\Product;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class InstallSimpleProducts implements
    DataPatchInterface, PatchVersionInterface
{
    /**
     * Setup class for category
     *
     * @var \Magento\CatalogSampleDataVenia\Setup\Category
     */
    protected $categorySetup;

    /**
     * Setup class for product attributes
     *
     * @var \Magento\CatalogSampleDataVenia\Setup\Attribute
     */
    protected $attributeSetup;

    /**
     * Setup class for products
     *
     * @var \Magento\CatalogSampleDataVenia\Setup\Product
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
     * @param Attribute $attributeSetup
     * @param Product $productSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup, Category $categorySetup,
                                Attribute $attributeSetup, Product $productSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetup = $categorySetup;
        $this->attributeSetup = $attributeSetup;
        $this->productSetup = $productSetup;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->attributeSetup->install(['Magento_CatalogSampleDataVenia::fixtures/attributes.csv']);
        $this->categorySetup->install(['Magento_CatalogSampleDataVenia::fixtures/categories.csv']);
        $this->productSetup->install(
            [
                'Magento_CatalogSampleDataVenia::fixtures/SimpleProduct/products_accessories.csv'
            ],
            [
                'Magento_CatalogSampleDataVenia::fixtures/SimpleProduct/images_accessories.csv'
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
    public static function getVersion(){
        return '0.0.0';
    }
}
