<?php

// Define base path to make includes work from any directory
$basePath = dirname(dirname(__DIR__)) . '/';

// Include required files with absolute paths
require_once $basePath . 'Database.php';
require_once $basePath . 'models/Quotation.php';
require_once $basePath . 'models/QuotationItem.php';
require_once $basePath . 'models/Settings.php';
require_once $basePath . 'vendor/autoload.php'; // TCPDF is installed via Composer

$config = require $basePath . 'config.php';
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
$settings = $settingsModel->get();

// Create PDF using TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');

// Set document information
$pdf->SetCreator('Quotation System');
$pdf->SetAuthor($settings['company_name']);
$pdf->SetTitle('Quotation ' . $quotation['quotation_no']);

// Remove header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Add content
$html = '
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid #000000;
    }
    th, td {
        padding: 5px;
        text-align: left;
    }
    .header {
        text-align: center;
        font-size: 12pt;
        font-weight: bold;
    }
    .logo {
        text-align: center;
    }
</style>

<div class="logo">
    <img src="' . $basePath . 'images/isu_logo.png" width="50" height="50">
</div>

<div class="header">
    Republic of the Philippines<br>
    ISABELA STATE UNIVERSITY<br>
    Roxas, Isabela<br>
    REQUEST FOR QUOTATION
</div>

<table>
    <tr>
        <td colspan="2">Date: ' . date('m/d/Y', strtotime($quotation['date'])) . '</td>
        <td colspan="2">Quotation No.: ' . htmlspecialchars($quotation['quotation_no']) . '</td>
    </tr>
    <tr>
        <td colspan="4">Company Name: ' . htmlspecialchars($settings['company_name']) . '</td>
    </tr>
    <tr>
        <td colspan="4">Address: ' . htmlspecialchars($settings['address']) . '</td>
    </tr>
    <tr>
        <td colspan="4">
            Please quote your lowest price on the item/s listed below, stating the shortest time delivery<br>
            and submit your quotation duly signed by your representation
        </td>
    </tr>
    <tr>
        <td colspan="4" align="right">MARI CHRIS B. MAGUSIB<br>Head, BAC Secretariat</td>
    </tr>
    <tr>
        <td colspan="4">
            Note:<br>
            1. Delivery period within ' . htmlspecialchars($settings['delivery_days']) . ' Calendar Days upon receipt of P.O.<br>
            2. Warranty shall be for period of six (6) months for supplies & materials, one (1) year for equipment, from date of<br>
            acceptance by the procuring entity.<br>
            3. Price validity shall be for a period of ' . htmlspecialchars($settings['price_validity_days']) . ' Calendar Days<br>
            4. Attach Certificate of PhilGEPS Registration, Mayor\'s Permit & DTI Registration.<br>
            5. Please check VAT REG <input type="checkbox" name="vat_reg"> or NON VAT <input type="checkbox" name="non_vat">.
        </td>
    </tr>
    <tr>
        <td colspan="2">NAME OF DEPARTMENT/OFFICE: ' . htmlspecialchars($quotation['department']) . '</td>
        <td colspan="2">PURPOSE: ' . htmlspecialchars($quotation['purpose']) . '</td>
    </tr>
</table>

<table>
    <tr>
        <th width="10%">ITEM NO.</th>
        <th width="10%">QTY.</th>
        <th width="15%">UNIT</th>
        <th width="40%">ITEM DESCRIPTION</th>
        <th width="10%">UNIT PRICE</th>
        <th width="15%">TOTAL AMOUNT</th>
    </tr>';

$grandTotal = 0;

foreach ($items as $item) {
    $html .= '
    <tr>
        <td>' . htmlspecialchars($item['item_no']) . '</td>
        <td>' . htmlspecialchars($item['quantity']) . '</td>
        <td>' . htmlspecialchars($item['unit']) . '</td>
        <td>' . htmlspecialchars($item['description']) . '</td>
        <td align="right">' . number_format($item['final_price'], 2) . '</td>
        <td align="right">' . number_format($item['total_amount'], 2) . '</td>
    </tr>';
    
    $grandTotal += $item['total_amount'];
}

// Fill remaining rows if less than 20 items
$remainingRows = 20 - count($items);
for ($i = 0; $i < $remainingRows; $i++) {
    $html .= '
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>';
}

$html .= '
    <tr>
        <td colspan="5" align="right">GRAND TOTAL</td>
        <td align="right">' . number_format($grandTotal, 2) . '</td>
    </tr>
</table>

<p>In connection with the above request, I/We quote you the item/s at prices noted above.</p>

<table style="border: none;">
    <tr>
        <td width="50%" style="border: none; text-align: center;">
            _____________________________<br>
            Printed Name/Signature<br>
            Canvasser
        </td>
        <td width="50%" style="border: none; text-align: center;">
            ' . htmlspecialchars($settings['printed_name']) . '<br>
            Printed Name/Signature<br>
            Supplier/Dealer
        </td>
    </tr>
</table>

<table style="border: none;">
    <tr>
        <td width="50%" style="border: none;">&nbsp;</td>
        <td width="50%" style="border: none;">
            Contact Number: ' . htmlspecialchars($settings['contact_number']) . '<br>
            Tel. Number: ' . htmlspecialchars($settings['tel_number'] ?? '') . '<br>
            email add: ' . htmlspecialchars($settings['email']) . '<br>
            FB Page: ' . htmlspecialchars($settings['fb_page'] ?? '') . '
        </td>
    </tr>
</table>

<div style="text-align: left; font-size: 8pt; margin-top: 20px;">
    ISUR-PrO-RFQ-007<br>
    Effectivity: January 6, 2026<br>
    Revision: 01
</div>
';

// Write HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('Quotation_' . $quotation['quotation_no'] . '.pdf', 'I');