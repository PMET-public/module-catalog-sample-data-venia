<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Model\Downloadable;

use Magento\Catalog\Model\ConfigFactory;
use Magento\CatalogSampleDataVenia\Setup\Product\Gallery;
use Magento\Downloadable\Model\Product\Type;
use Magento\Eav\Model\Config;
use Magento\Framework\App\State;
use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Downloadable\Api\Data\SampleInterfaceFactory as SampleFactory;
use Magento\Downloadable\Api\Data\LinkInterfaceFactory as LinkFactory;
use Magento\Framework\App\ObjectManager;
use Magento\CatalogSampleDataVenia\Model\Downloadable\Converter as DownloadableConverter;
use Magento\Catalog\Model\ProductFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Setup downloadable product
 */
class Product extends \Magento\CatalogSampleDataVenia\Setup\Product
{
    /**
     * @var string
     */
    protected $productType = Type::TYPE_DOWNLOADABLE;

    /**
     * @var DownloadableConverter $converter
     */
    protected $converter;

    /**
     * @var array
     */
    protected $downloadableData = [];

    /**
     * @var SampleFactory
     */
    protected $sampleFactory;

    /**
     * @var LinkFactory
     */
    protected $linkFactory;

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
        DownloadableConverter $converter,
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
     * {@inheritdoc}
     */
    public function install(array $productFixtures, array $galleryFixtures, array $downloadableFixtures = [])
    {
        foreach ($downloadableFixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);
            if (!file_exists($fileName)) {
                continue;
            }
            $rows = $this->csvReader->getData($fileName);
            $header = array_shift($rows);

            foreach ($rows as $row) {
                $data = [];
                foreach ($row as $key => $value) {
                    $data[$header[$key]] = $value;
                }
                $row = $data;

                $sku = $row['product_sku'];
                if (!isset($this->downloadableData[$sku])) {
                    $this->downloadableData[$sku] = [];
                }
                $this->downloadableData[$sku] =
                    $this->converter->getDownloadableData($row, $this->downloadableData[$sku]);
                $this->downloadableData[$sku]['sample'] = $this->converter->getSamplesInfo();
            }
        }

        parent::install($productFixtures, $galleryFixtures);
    }

    /**
     * @inheritdoc
     */
    protected function prepareProduct($product, $data)
    {
        if (isset($this->downloadableData[$data['sku']])) {
            $extension = $product->getExtensionAttributes();
            $links = [];
            foreach ($this->downloadableData[$data['sku']]['link'] as $linkData) {
                $link = $this->getLinkFactory()->create(['data' => $linkData]);
                if (isset($linkData['type'])) {
                    $link->setLinkType($linkData['type']);
                }
                if (isset($linkData['file'])) {
                    $link->setFile($linkData['file']);
                }
                if (isset($linkData['file_content'])) {
                    $link->setLinkFileContent($linkData['file_content']);
                }
                $link->setId(null);
                if (isset($linkData['sample']['type'])) {
                    $link->setSampleType($linkData['sample']['type']);
                }
                if (isset($linkData['sample']['file'])) {
                    $link->setSampleFileData($linkData['sample']['file']);
                }
                if (isset($linkData['sample']['url'])) {
                    $link->setSampleUrl($linkData['sample']['url']);
                }
                if (isset($linkData['sample']['file_content'])) {
                    $link->setSampleFileContent($linkData['file_content']);
                }
                $link->setStoreId($product->getStoreId());
                $link->setWebsiteId($product->getStore()->getWebsiteId());
                $link->setProductWebsiteIds($product->getWebsiteIds());
                if (!$link->getSortOrder()) {
                    $link->setSortOrder(1);
                }
                if (null === $link->getPrice()) {
                    $link->setPrice(0);
                }
                if ($link->getIsUnlimited()) {
                    $link->setNumberOfDownloads(0);
                }
                $links[] = $link;
            }
            $extension->setDownloadableProductLinks($links);

            $samples = [];
            foreach ($this->downloadableData[$data['sku']]['sample'] as $sampleData) {
                $sample = $this->getSampleFactory()->create(['data' => $sampleData]);
                $sample->setId(null);
                $sample->setStoreId($product->getStoreId());
                if (isset($sampleData['type'])) {
                    $sample->setSampleType($sampleData['type']);
                }
                if (isset($sampleData['file'])) {
                    $sample->setFile($sampleData['file']);
                }
                if (isset($sampleData['sample_url'])) {
                    $sample->setSampleUrl($sampleData['sample_url']);
                }
                if (!$sample->getSortOrder()) {
                    $sample->setSortOrder(1);
                }
                $samples[] = $sample;
            }
            $extension->setDownloadableProductSamples($samples);

            $product->setDownloadableData($this->downloadableData[$data['sku']]);
            $product->setExtensionAttributes($extension);
        }
        $this->setVirtualStockData($product);
        return $this;
    }

    /**
     * Get link interface factory
     *
     * @deprecated
     * @return LinkFactory
     */
    private function getLinkFactory()
    {
        if (!$this->linkFactory) {
            $this->linkFactory = ObjectManager::getInstance()->get(
                '\Magento\Downloadable\Api\Data\LinkInterfaceFactory'
            );
        }
        return $this->linkFactory;
    }

    /**
     * Get sample interface factory
     *
     * @deprecated
     * @return SampleFactory
     */
    private function getSampleFactory()
    {
        if (!$this->sampleFactory) {
            $this->sampleFactory = ObjectManager::getInstance()->get(
                '\Magento\Downloadable\Api\Data\SampleInterfaceFactory'
            );
        }
        return $this->sampleFactory;
    }
}
