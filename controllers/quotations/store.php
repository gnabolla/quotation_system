<?php

require_once 'Database.php';
require_once 'models/Quotation.php';
require_once 'models/QuotationItem.php';

$config = require 'config.php';
$db = new Database($config['database']);
$quotationModel = new Quotation($db);
$quotationItemModel = new QuotationItem($db);

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /quotations');
    exit;
}

// Create the quotation
$quotationId = $quotationModel->create([
    'quotation_no' => $_POST['quotation_no'],
    'date' => $_POST['date'],
    'department' => $_POST['department'],
    'purpose' => $_POST['purpose']
]);

// Add items to the quotation
if (isset($_POST['items']) && is_array($_POST['items'])) {
    foreach ($_POST['items'] as $index => $item) {
        // Skip empty rows
        if (empty($item['description'])) {
            continue;
        }
        
        $quantity = (int)$item['quantity'];
        $supplierPrice = (float)$item['supplier_price'];
        $markupPercentage = (float)$item['markup_percentage'];
        
        // Calculate final price with markup
        $finalPrice = $supplierPrice * (1 + ($markupPercentage / 100));
        $totalAmount = $finalPrice * $quantity;
        
        $quotationItemModel->create([
            'quotation_id' => $quotationId,
            'item_no' => $index + 1,
            'quantity' => $quantity,
            'unit' => $item['unit'],
            'description' => $item['description'],
            'supplier_price' => $supplierPrice,
            'markup_percentage' => $markupPercentage,
            'final_price' => $finalPrice,
            'total_amount' => $totalAmount
        ]);
    }
}

// Redirect to the show page
header("Location: /quotations/show?id=$quotationId");
exit;