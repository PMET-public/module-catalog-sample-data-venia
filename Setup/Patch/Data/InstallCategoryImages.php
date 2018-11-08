<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;




class InstallCategoryImages implements DataPatchInterface
{

    /** @var CategoryRepositoryInterface  */
    protected $categoryRepository;

    /** @var CollectionFactory  */
    protected $collectionFactory;

    protected $categoryImages = ['Accessories'=>'carefree.jpg'];

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CollectionFactory $collectionFactory
    )

    {
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
    }


    public function apply()
    {
        foreach($this->categoryImages as $cat=>$categoryImage) {
            $collection = $this->collectionFactory
                ->create()
                ->addAttributeToFilter('name', $cat)
                ->setPageSize(1);
            if ($collection->getSize()) {
                $categoryId = $collection->getFirstItem()->getId();
            }
            $category = $this->categoryRepository->get($categoryId);

            $category->setCustomAttribute('image', $categoryImage);
            $this->categoryRepository->save($category);
        }

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
        return [InstallSimpleProducts::class];
    }
}
