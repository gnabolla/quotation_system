<?php

require_once 'Database.php';
require_once 'models/Settings.php';

$config = require 'config.php';
$db = new Database($config['database']);
$settingsModel = new Settings($db);

$settings = $settingsModel->get();

require 'views/settings/index.view.php';