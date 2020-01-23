<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CatalogSampleDataVenia\Model\Bundle;

/**
 * Convert data for bundle product
 */
class Converter extends \Magento\CatalogSampleDataVenia\Setup\Product\Converter
{
    /**
     * Convert CSV format row to array
     *
     * @param array $row
     * @return array
     */
    public function convertRow($row)
    {
        $data = parent::convertRow($row);
        if (!empty($row['bundle_options'])) {
            $bundleData = $this->convertBundleOptions($row['bundle_options']);
            $data = array_merge($data, $bundleData);
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    protected function convertField(&$data, $field, $value)
    {
        return $field == 'bundle_options';
    }

    /**
     * Convert bundle options
     *
     * @param array $bundleOptionsData
     * @return array
     */
    protected function convertBundleOptions($bundleOptionsData)
    {
        $resultOptions = [];
        $resultSelections = [];
        $bundleOptions = explode("\n", $bundleOptionsData);
        $optionPosition = 1;
        foreach ($bundleOptions as $option) {
            if (strpos($option, ':') === false) {
                continue;
            }
            $optionData = explode(':', $option);
            if (empty($optionData[0]) || empty($optionData[1])) {
                continue;
            }
            $optionType = 'select';
            $optionName = $optionData[0];
            if (strpos($optionName, '|') !== false) {
                $optionNameData = explode('|', $optionName);
                $optionName = $optionNameData[0];
                $optionType = $optionNameData[1];
                $optionRequired = $optionNameData[2];
                $optionPosition = $optionNameData[3];
            }
            $resultOptions[] = [
                'title' => $optionName,
                'option_id' => '',
                'delete' => '',
                'type' => $optionType,
                'required' => $optionRequired,
                'position' => $optionPosition,
            ];
            $skuList = explode(',', $optionData[1]);
            $selections = [];
            $selectionPosition = 1;
            $default = true;
            foreach ($skuList as $skuInfo) {
                if (strpos($skuInfo, '|') !== false) {
                    $skuDetails = explode('|', $skuInfo);
                    $sku = $skuDetails[0];
                    $skuIsDefault = $skuDetails[1];
                    $skuDefaultQuantity = $skuDetails[2];
                    $skuUserDefined = $skuDetails[3];
                }
                $productId = $this->getProductIdBySku($sku);
                if (!$productId) {
                    continue;
                }
                $selections[] = [
                    'selection_id' => '',
                    'option_id' => '',
                    'product_id' => $productId,
                    'delete' => '',
                    'selection_price_value' => '0.00',
                    'selection_price_type' => '0',
                    'selection_qty' => $skuDefaultQuantity,
                    'selection_can_change_qty' => $skuUserDefined,
                    'position' => $selectionPosition++,
                    'is_default' => $skuIsDefault,
                ];
                $default = false;
            }
            $resultSelections[] = $selections;
        }
        return ['bundle_options_data' => $resultOptions, 'bundle_selections_data' => $resultSelections];
    }
}
