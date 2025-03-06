<?php

require_once 'Database.php';
require_once 'models/Quotation.php';
require_once 'models/QuotationItem.php';
require_once 'models/Settings.php';

$config = require 'config.php';
$db = new Database($config['database']);
$quotationModel = new Quotation($db);
$quotationItemModel = new QuotationItem($db);
$settingsModel = new Settings($db);

$id = $_GET['id'] ?? 0;

$quotation = $quotationModel->find($id);
if (!$quotation) {
    header('Location: /quotations');
    exit;
}

$items = $quotationItemModel->findByQuotationId($id);
$totalProfit = $quotationItemModel->calculateTotalProfit($id);
$settings = $settingsModel->get();

require 'views/quotations/show.view.php';