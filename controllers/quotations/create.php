<?php

require_once 'Database.php';
require_once 'models/Quotation.php';
require_once 'models/Settings.php';

$config = require 'config.php';
$db = new Database($config['database']);
$quotationModel = new Quotation($db);
$settingsModel = new Settings($db);

$settings = $settingsModel->get();
$quotationNo = $quotationModel->generateQuotationNumber();

require 'views/quotations/create.view.php';