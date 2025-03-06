<?php

require_once 'models/Quotation.php';

$db = new Database(require 'config.php'['database']);
$quotationModel = new Quotation($db);

$quotations = $quotationModel->all();

require 'views/quotations/index.view.php';