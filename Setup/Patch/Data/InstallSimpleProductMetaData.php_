<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\CatalogSampleDataVenia\Setup\ProductImport;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\ConfigurableSampleDataVenia\Setup\SetSession;

/**
* Patch is mechanism, that allows to do atomic upgrade data changes
*/
class InstallSimpleProductMetaData implements
    DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

      /**
     * @var \Magento\CatalogSampleDataVenia\Setup\ProductImport
     */
    private $productImport;


    /** @var \Magento\ConfigurableSampleDataVenia\Setup\Swatches */
    protected $swatches;

    /**
     * InstallSimpleProductMetaData constructor.
     * @param SetSession $setSession
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ProductImport $productImport
     */
    public function __construct(
        SetSession $setSession,
        ModuleDataSetupInterface $moduleDataSetup,
        ProductImport $productImport)
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productImport = $productImport;
    }


    public function apply()
    {
         $this->productImport->install('Magento_CatalogSampleDataVenia','fixtures/SimpleProduct/products_accessories_metadata.csv');
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
            InstallSimpleProducts::class

        ];
    }
    public static function getVersion(){
        return '0.0.0';
    }
}
