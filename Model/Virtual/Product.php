<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Model\Virtual;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ConfigFactory;
use Magento\CatalogSampleDataVenia\Model\Bundle\Converter;
use Magento\CatalogSampleDataVenia\Setup\Product\Gallery;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\State;
use Magento\Eav\Model\Config;
use Magento\Catalog\Model\Product\Type as ProductType;

class Product extends \Magento\CatalogSampleDataVenia\Setup\Product
{
    /**
     * @var string
     */
    protected $productType = ProductType::TYPE_VIRTUAL;


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

}
