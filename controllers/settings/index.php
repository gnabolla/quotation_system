<?php

require_once 'models/Settings.php';

$db = new Database(require 'config.php'['database']);
$settingsModel = new Settings($db);

$settings = $settingsModel->get();

require 'views/settings/index.view.php';