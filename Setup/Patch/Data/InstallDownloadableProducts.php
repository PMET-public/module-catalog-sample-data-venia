<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;


use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\CatalogSampleDataVenia\Setup\Category;
use Magento\CatalogSampleDataVenia\Setup\Attribute;
use Magento\CatalogSampleDataVenia\Model\Downloadable\Product;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;

class InstallDownloadableProducts implements DataPatchInterface
{

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * @var Product
     */
    private $downloadableProduct;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * InstallDownloadableProducts constructor.
     * @param Category $category
     * @param Attribute $attribute
     * @param Product $product
     * @param StoreManagerInterface|null $storeManager
     */
    public function __construct(
        Category $category,
        Attribute $attribute,
        Product $product,
        StoreManagerInterface $storeManager = null
    ) {
        $this->category = $category;
        $this->attribute = $attribute;
        $this->downloadableProduct = $product;
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
    }

    public function apply()
    {
        $this->attribute->install(['Magento_CatalogSampleDataVenia::fixtures/Downloadable/attributes.csv']);
        $this->downloadableProduct->install(
            ['Magento_CatalogSampleDataVenia::fixtures/Downloadable/products_download.csv'],
            ['Magento_CatalogSampleDataVenia::fixtures/Downloadable/images_products_download.csv'],
            ['Magento_CatalogSampleDataVenia::fixtures/Downloadable/downloadable_data.csv']
        );
    }

    public static function getDependencies()
    {
        return [InstallVirtualProducts::class];
    }

    public function getAliases()
    {
        return [];
    }
}
