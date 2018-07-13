<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Setup;

use Magento\Framework\Setup;

class Installer implements Setup\SampleData\InstallerInterface
{
    /**
     * Setup class for category
     *
     * @var \Magento\CatalogSampleData\Model\Category
     */
    protected $categorySetup;

    /**
     * Setup class for product attributes
     *
     * @var \Magento\CatalogSampleData\Model\Attribute
     */
    protected $attributeSetup;

    /**
     * Setup class for products
     *
     * @var \Magento\CatalogSampleData\Model\Product
     */
    protected $productSetup;

    /**
     * @param \Magento\CatalogSampleData\Model\Category $categorySetup
     * @param \Magento\CatalogSampleData\Model\Attribute $attributeSetup
     * @param \Magento\CatalogSampleData\Model\Product $productSetup
     */
    public function __construct(
        \Magento\CatalogSampleDataVenia\Model\Category $categorySetup,
        \Magento\CatalogSampleDataVenia\Model\Attribute $attributeSetup,
        \Magento\CatalogSampleDataVenia\Model\Product $productSetup
    ) {
        $this->categorySetup = $categorySetup;
        $this->attributeSetup = $attributeSetup;
        $this->productSetup = $productSetup;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        $this->attributeSetup->install(['Magento_CatalogSampleData::fixtures/attributes.csv']);
        $this->categorySetup->install(['Magento_CatalogSampleData::fixtures/categories.csv']);
        $this->productSetup->install(
            [
                'Magento_CatalogSampleDataVenia::fixtures/SimpleProduct/products_accessories.csv'
            ],
            [
                'Magento_CatalogSampleDataVenia::fixtures/SimpleProduct/images_accessories.csv'
            ]
        );
    }
}
