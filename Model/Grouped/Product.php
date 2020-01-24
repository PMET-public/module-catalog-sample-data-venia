<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Model\Grouped;

use Magento\Catalog\Model\ConfigFactory;
use Magento\Catalog\Model\Product\Initialization\Helper\ProductLinks;
use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogSampleDataVenia\Setup\Product\Gallery;
use Magento\Eav\Model\Config;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\State;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Store\Model\StoreManagerInterface;

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
     * @var ProductLinks
     */
    private $productLinksHelper;

    /**
     * Product constructor.
     * @param SampleDataContext $sampleDataContext
     * @param ProductFactory $productFactory
     * @param ConfigFactory $catalogConfig
     * @param Converter $converter
     * @param Gallery $gallery
     * @param StoreManagerInterface $storeManager
     * @param Config $eavConfig
     * @param State $appState
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ProductFactory $productFactory,
        ConfigFactory $catalogConfig,
        Converter $converter,
        Gallery $gallery,
        StoreManagerInterface $storeManager,
        Config $eavConfig,
        State $appState
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
     * @return ProductLinks
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
