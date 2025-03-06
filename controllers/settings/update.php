<?php

require_once 'Database.php';
require_once 'models/Settings.php';

$config = require 'config.php';
$db = new Database($config['database']);
$settingsModel = new Settings($db);

// Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /settings');
    exit;
}

$updated = $settingsModel->update([
    'id' => $_POST['id'],
    'company_name' => $_POST['company_name'],
    'address' => $_POST['address'],
    'contact_number' => $_POST['contact_number'],
    'tel_number' => $_POST['tel_number'] ?? null,
    'email' => $_POST['email'],
    'fb_page' => $_POST['fb_page'] ?? null,
    'delivery_days' => (int)$_POST['delivery_days'],
    'price_validity_days' => (int)$_POST['price_validity_days'],
    'printed_name' => $_POST['printed_name']
]);

// Redirect back to settings page
header('Location: /settings?success=' . ($updated ? '1' : '0'));
exit;