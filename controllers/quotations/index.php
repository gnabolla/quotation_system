<?php

require_once 'Database.php';
require_once 'models/Quotation.php';

$config = require 'config.php';
$db = new Database($config['database']);
$quotationModel = new Quotation($db);

$quotations = $quotationModel->all();

require 'views/quotations/index.view.php';