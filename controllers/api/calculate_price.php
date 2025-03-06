<?php

// Set response content type to JSON
header('Content-Type: application/json');

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['supplier_price']) || !isset($data['markup_percentage']) || !isset($data['quantity'])) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

// Get and validate values
$supplierPrice = (float) $data['supplier_price'];
$markupPercentage = (float) $data['markup_percentage'];
$quantity = (int) $data['quantity'];

// Calculate final price with markup
$finalPrice = $supplierPrice * (1 + ($markupPercentage / 100));
$totalAmount = $finalPrice * $quantity;
$profit = ($finalPrice - $supplierPrice) * $quantity;

// Return the calculated values
echo json_encode([
    'final_price' => round($finalPrice, 2),
    'total_amount' => round($totalAmount, 2),
    'profit' => round($profit, 2)
]);