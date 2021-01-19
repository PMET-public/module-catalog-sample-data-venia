<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogSampleDataVenia\Model\Downloadable;

use Magento\Catalog\Model\Category\TreeFactory;
use Magento\Catalog\Model\ResourceModel\Category\TreeFactory as ResourceModelTreeFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Downloadable\Api\Data\File\ContentInterfaceFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory as OptionCollectionFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Filesystem;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Filesystem\DriverInterface;

/**
 * Class Converter
 */
class Converter extends \Magento\CatalogSampleDataVenia\Setup\Product\Converter
{
    /**
     * @var ContentInterfaceFactory
     */
    private $fileContentFactory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /** @var DriverInterface */
    protected $driverInterface;
   
    /**
     * Converter constructor.
     * @param TreeFactory $categoryTreeFactory
     * @param ResourceModelTreeFactory $categoryResourceTreeFactory
     * @param Config $eavConfig
     * @param CategoryCollection $categoryCollectionFactory
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param OptionCollectionFactory $attrOptionCollectionFactory
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ContentInterfaceFactory|null $fileContentFactory
     * @param Filesystem|null $filesystem
     * @param DriverInterface $driverInterface
     */
    public function __construct(
        TreeFactory $categoryTreeFactory,
        ResourceModelTreeFactory $categoryResourceTreeFactory,
        Config $eavConfig,
        CategoryCollection $categoryCollectionFactory,
        AttributeCollectionFactory $attributeCollectionFactory,
        OptionCollectionFactory $attrOptionCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        ContentInterfaceFactory $fileContentFactory = null,
        Filesystem $filesystem = null,
        DriverInterface $driverInterface
    ) {
        parent::__construct(
            $categoryTreeFactory,
            $categoryResourceTreeFactory,
            $eavConfig,
            $categoryCollectionFactory,
            $attributeCollectionFactory,
            $attrOptionCollectionFactory,
            $productCollectionFactory
        );
        $this->fileContentFactory = $fileContentFactory ?: ObjectManager::getInstance()->get(
            ContentInterfaceFactory::class
        );
        $this->filesystem = $filesystem ?: ObjectManager::getInstance()->get(
            Filesystem::class
        );

        $this->driverInterface = $driverInterface;
    }


    /**
     * Get downloadable data from array
     *
     * @param array $row
     * @param array $downloadableData
     * @return array
     */
    public function getDownloadableData($row, $downloadableData = [])
    {
        $separatedData = $this->groupDownloadableData($row);
        $formattedData = $this->getFormattedData($separatedData);
        foreach (array_keys($formattedData) as $dataType) {
            $downloadableData[$dataType][] = $formattedData[$dataType];
        }

        return $downloadableData;
    }

    /**
     * Group downloadable data by link and sample array keys.
     *
     * @param array $downloadableData
     * @return array
     */
    public function groupDownloadableData($downloadableData)
    {
        $groupedData = [];
        foreach ($downloadableData as $dataKey => $dataValue) {
            if (!empty($dataValue)) {
                if ((preg_match('/^(link_item)/', $dataKey, $matches)) && is_array($matches)) {
                    $groupedData['link'][$dataKey] = $dataValue;
                }
            }
            unset($dataKey);
            unset($dataValue);
        }

        return $groupedData;
    }

    /**
     * Will format data corresponding to the product sample data array values.
     *
     * @param array $groupedData
     * @return array
     */
    public function getFormattedData($groupedData)
    {
        $formattedData = [];
        foreach (array_keys($groupedData) as $dataType) {
            if ($dataType == 'link') {
                $formattedData['link'] = $this->formatDownloadableLinkData($groupedData['link']);
            }
        }

        return $formattedData;
    }

    /**
     * Format downloadable link data
     *
     * @param array $linkData
     * @return array
     */
    public function formatDownloadableLinkData($linkData)
    {
        $linkItems = [
            'link_item_title',
            'link_item_price',
            'link_item_file',
        ];
        foreach ($linkItems as $csvRow) {
            $linkData[$csvRow] = isset($linkData[$csvRow]) ? $linkData[$csvRow] : '';
        }
        $directory = $this->getFilesystem()->getDirectoryRead(DirectoryList::MEDIA);
        $linkPath = $directory->getAbsolutePath('downloadable/files/links' . $linkData['link_item_file']);
        $data = base64_encode($this->driverInterface->fileGetContents($linkPath));
        $content = $this->getFileContent()->setFileData($data)
            ->setName('vdl01_product.pdf');
        $link = [
            'is_delete' => '',
            'link_id' => '0',
            'title' => $linkData['link_item_title'],
            'price' => $linkData['link_item_price'],
            'number_of_downloads' => '0',
            'is_shareable' => '2',
            'type' => 'file',
            'file' => json_encode([['file' => $linkData['link_item_file'], 'status' => 'old']]),
            'sort_order' => '',
            'link_file_content' => $content
        ];

        return $link;
    }

    /**
     * Returns information about product's samples
     * @return array
     */
    public function getSamplesInfo()
    {
        $directory = $this->getFilesystem()->getDirectoryRead(DirectoryList::MEDIA);
        $linkPath = $directory->getAbsolutePath(
            'downloadable/files/samples/v/d/vdl01_product.pdf'
        );
        $data = base64_encode(file_get_contents($linkPath));
        $content = $this->getFileContent()->setFileData($data)
            ->setName('vdl01_product.pdf');
        $sample = [
            'is_delete' => '',
            'sample_id' => '0',
            'file' => json_encode([[
                'file' => '/v/d/vdl01_product.pdf',
                'status' => 'old',
            ]]),
            'type' => 'file',
            'sort_order' => '',
            'sample_file_content' => $content
        ];

        $samples = [];
        for ($i = 1; $i <= 3; $i++) {
            $sample['title'] = 'Sample #' . $i;
            $samples[] = $sample;
        }

        return $samples;
    }

    /**
     * @return \Magento\Downloadable\Api\Data\File\ContentInterface
     * @deprecated
     */
    private function getFileContent()
    {
        return $this->fileContentFactory->create();
    }

    /**
     * @return Filesystem
     * @deprecated
     */
    private function getFilesystem()
    {
        return $this->filesystem;
    }
}
