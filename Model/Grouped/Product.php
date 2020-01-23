<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Model\Grouped;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;

/**
 * Setup grouped product
 */
class Product extends \Magento\CatalogSampleDataVenia\Setup\Product
{
    /**
     * @var string
     */
    protected $productType = \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE;

    /**
     * @var \Magento\Catalog\Model\Product\Initialization\Helper\ProductLinks
     */
    private $productLinksHelper;

    /**
     * Product constructor.
     * @param SampleDataContext $sampleDataContext
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\ConfigFactory $catalogConfig
     * @param \Magento\CatalogSampleDataVenia\Model\Grouped\Converter $converter
     * @param \Magento\Framework\Setup\SampleData\FixtureManager $fixtureManager
     * @param \Magento\CatalogSampleDataVenia\Setup\Product\Gallery $gallery
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ConfigFactory $catalogConfig,
        \Magento\CatalogSampleDataVenia\Model\Grouped\Converter $converter,
        \Magento\Framework\Setup\SampleData\FixtureManager $fixtureManager,
        \Magento\CatalogSampleDataVenia\Setup\Product\Gallery $gallery,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\App\State $appState
    ) {
        parent::__construct(
            $sampleDataContext,
            $productFactory,
            $catalogConfig,
            $converter,
            $gallery,
            $storeManager,
            $eavConfig,
            $appState
        );
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param array $data
     * @return $this
     */
    protected function prepareProduct($product, $data)
    {
        $this->getProductLinksHelper()->initializeLinks($product, $data['grouped_link_data']);
        $product->unsetData('grouped_link_data');
        return $this;
    }

    /**
     * Get product links helper
     *
     * @deprecated
     * @return \Magento\Catalog\Model\Product\Initialization\Helper\ProductLinks
     */
    private function getProductLinksHelper()
    {

        if (!($this->productLinksHelper)) {
            return ObjectManager::getInstance()->get(
                '\Magento\Catalog\Model\Product\Initialization\Helper\ProductLinks'
            );
        } else {
            return $this->productLinksHelper;
        }
    }
}
