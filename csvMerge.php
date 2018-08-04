<?php
    $duplicatItems = 0;
    $itemsMissing = 0;
    $originalInventoryColumns = ['Item #', 'Item Title', 'Collection', 'Product Type', 'WebDescription', 'Unit Price', 'MSRP', 'Image'];
    $updatedInventoryColumns = ['Style #', 'Item #', 'Item Title', 'Unit Price', 'MSRP', 'On-Hand', 'WebDescription'];
    $finalInventoryColumns = ['Style #', 'Item #', 'Item Title', 'Unit Price', 'MSRP', 'On-Hand', 'WebDescription', 'Collection', 'Product Type', 'Image'];

    function convertCsvToArray($fileName, $columnNames) {
        $inventory = array();

        if(($handle = fopen($fileName, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, ",")) !== FALSE) {
                $num = count($data);
                $productArray = array();

                for($i = 0; $i < $num; $i++) {
                    $columnName = $columnNames[$i];
                    $columnData = $data[$i];
                    $productArray[$columnName] = $columnData;
                }
                array_push($inventory, $productArray);
            }
            fclose($handle);
        }
        return $inventory;
    }

    function matchOriginalItem($originalInventory, $styleNumber) {
        for($i = 0; $i < count($originalInventory); $i++) {
            $itemNumber = $originalInventory[$i]['Item #'];
            if($itemNumber == $styleNumber) {
                return $originalInventory[$i];
            }
        }
        return null;
    }

    function createCsv($columns, $inventory) {
        // Add column headers in
        array_unshift($inventory, $columns);

        $file = fopen('FullInventory-merged.csv', 'w');
        foreach ($inventory as $fields) {
            fputcsv($file, $fields);
        }
        fclose($file);
    }

    $originalInventory = convertCsvToArray('OriginalInventory.csv', $originalInventoryColumns);
    $updatedInventory = convertCsvToArray('UpdatedInventory.csv', $updatedInventoryColumns);

    // Remove headers
    array_shift($originalInventory);
    array_shift($updatedInventory);

    $finalInventory = array();

    // Add items from updated inventory
    for($i = 0; $i < count($updatedInventory); $i++) {
        $updatedItem = $updatedInventory[$i];
        $styleNumber = $updatedItem['Style #'];

        $originalItem = matchOriginalItem($originalInventory, $styleNumber);
        $newItem = [
            'Style #' => trim($updatedItem['Style #']),
            'Item #'  => trim($updatedItem['Item #']),
            'Item Title' => trim($updatedItem['Item Title']),
            'Unit Price' => trim($updatedItem['Unit Price']),
            'MSRP' => trim($updatedItem['MSRP']),
            'On-Hand' => trim($updatedItem['On-Hand']),
            'WebDescription' => trim($updatedItem['WebDescription']),
        ];

        if($originalItem) {
            $duplicatItems++;
            $newItem['Collection'] = trim($originalItem['Collection']);
            $newItem['Product Type'] = trim($originalItem['Product Type']);
            $newItem['Image'] = trim($originalItem['Image']);
        }  
        else {
            $itemsMissing++;
            $newItem['Collection'] = 'N/A';
            $newItem['Product Type'] = 'N/A';
            $newItem['Image'] = 'N/A';
        }
        array_push($finalInventory, $newItem);
    }

    echo "Duplicate Style #s: " . $duplicatItems . "\n";
    echo "Items missing from original CSV: " . $itemsMissing . "\n";

    createCsv($finalInventoryColumns, $finalInventory);
?>