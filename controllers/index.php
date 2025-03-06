<?php

require_once 'Database.php';

$config = require 'config.php';
$db = new Database($config['database']);

require 'views/index.view.php';
