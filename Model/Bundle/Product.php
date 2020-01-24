<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Model\Bundle;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Bundle\Api\Data\OptionInterfaceFactory as OptionFactory;
use Magento\Bundle\Api\Data\LinkInterfaceFactory as LinkFactory;
use Magento\Catalog\Api\ProductRepositoryInterface as ProductRepository;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ConfigFactory;
use Magento\Framework\App\ObjectManager;
use Magento\CatalogSampleDataVenia\Model\Bundle\Converter;
use Magento\CatalogSampleDataVenia\Setup\Product\Gallery;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Eav\Model\Config;
use Magento\Framework\App\State as AppState;

/**
 * Setup bundle product
 */
class Product extends \Magento\CatalogSampleDataVenia\Setup\Product
{
    /**
     * @var string
     */
    protected $productType = \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE;

    /**
     * @var OptionFactory
     */
    private $optionFactory;

    /**
     * Product constructor.
     * @param SampleDataContext $sampleDataContext
     * @param ProductFactory $productFactory
     * @param ConfigFactory $catalogConfig
     * @param Converter $converter
     * @param Gallery $gallery
     * @param StoreManagerInterface $storeManager
     * @param Config $eavConfig
     * @param AppState $appState
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        ProductFactory $productFactory,
        ConfigFactory $catalogConfig,
        Converter $converter,
        Gallery $gallery,
        StoreManagerInterface $storeManager,
        Config $eavConfig,
        AppState $appState
    ) {
        $this->eavConfig = $eavConfig;
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
     * @var LinkFactory
     */
    private $linkFactory;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @inheritdoc
     */
    protected function prepareProduct($product, $data)
    {
        $product
            ->setCanSaveConfigurableAttributes(true)
            ->setCanSaveBundleSelections(true)
            ->setPriceType(0)
            ->setShipmentType(0)
            ->setSkuType(0)
            ->setWeightType(0)
            ->setPriceView(0);
        $bundleOptionsData = $product->getBundleOptionsData();
        $options = [];
        foreach ($bundleOptionsData as $key => $optionData) {
            $option = $this->getOptionFactory()->create(['data' => $optionData]);
            $option->setSku($product->getSku());
            $option->setOptionId(null);

            $links = [];
            $bundleLinks = $product->getBundleSelectionsData();
            foreach ($bundleLinks[$key] as $linkData) {
                $linkProduct = $this->getProductRepository()->getById($linkData['product_id']);
                $link = $this->getLinkFactory()->create(['data' => $linkData]);
                $link->setSku($linkProduct->getSku());
                $link->setQty($linkData['selection_qty']);

                if (array_key_exists('selection_can_change_qty', $linkData)) {
                    $link->setCanChangeQuantity($linkData['selection_can_change_qty']);
                }
                $links[] = $link;
            }
            $option->setProductLinks($links);
            $options[] = $option;
        }

        $extension = $product->getExtensionAttributes();
        $extension->setBundleProductOptions($options);
        $product->setExtensionAttributes($extension);

        return $this;
    }

    /**
     * Get option interface factory
     *
     * @deprecated
     * @return \Magento\Bundle\Api\Data\OptionInterfaceFactory
     */
    private function getOptionFactory()
    {
        if (!$this->optionFactory) {
            $this->optionFactory = ObjectManager::getInstance()->get(
                '\Magento\Bundle\Api\Data\OptionInterfaceFactory'
            );
        }
        return $this->optionFactory;
    }

    /**
     * Get bundle link interface factory
     *
     * @deprecated
     * @return \Magento\Bundle\Api\Data\LinkInterfaceFactory
     */
    private function getLinkFactory()
    {
        if (!$this->linkFactory) {
            $this->linkFactory = ObjectManager::getInstance()->get(
                '\Magento\Bundle\Api\Data\LinkInterfaceFactory'
            );
        }
        return $this->linkFactory;
    }

    /**
     * Get product repository
     *
     * @deprecated
     * @return \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private function getProductRepository()
    {
        if (!$this->productRepository) {
            $this->productRepository = ObjectManager::getInstance()->get(
                '\Magento\Catalog\Api\ProductRepositoryInterface'
            );
        }
        return $this->productRepository;
    }
}
