<?php

require_once 'models/Quotation.php';
require_once 'models/Settings.php';

$db = new Database(require 'config.php'['database']);
$quotationModel = new Quotation($db);
$settingsModel = new Settings($db);

$settings = $settingsModel->get();
$quotationNo = $quotationModel->generateQuotationNumber();

require 'views/quotations/create.view.php';