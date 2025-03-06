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

// Create a new instance of TCPDF using the original PDF as template
// We'll use TCPDF's advanced functionality to use an existing PDF as template
class MYPDF extends TCPDF {
    public function Header() {
        // No header - we'll use the template
    }
    
    public function Footer() {
        // No footer - we'll use the template
    }
}

// Create PDF using TCPDF
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8');

// Set document information
$pdf->SetCreator('Quotation System');
$pdf->SetAuthor($settings['company_name']);
$pdf->SetTitle('Quotation ' . $quotation['quotation_no']);

// Remove header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Import the original RFQ PDF as a template
// Assuming the original PDF template is stored at 'templates/rfq_template.pdf'
$templatePath = $basePath . 'templates/rfq_template.pdf';
$pdf->setSourceFile($templatePath);
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());

// Set font for overlaying text
$pdf->SetFont('helvetica', '', 10);

// Now we'll overlay our dynamic data on top of the template
// Date
$pdf->SetXY(175, 28);
$pdf->Cell(30, 5, date('m/d/Y', strtotime($quotation['date'])), 0, 0, 'L');

// Quotation Number
$pdf->SetXY(175, 37);
$pdf->Cell(30, 5, $quotation['quotation_no'], 0, 0, 'L');

// Company Name
$pdf->SetXY(55, 46);
$pdf->Cell(100, 5, $settings['company_name'], 0, 0, 'L');

// Address
$pdf->SetXY(55, 55);
$pdf->Cell(100, 5, $settings['address'], 0, 0, 'L');

// Delivery period
$pdf->SetXY(75, 92);
$pdf->Cell(30, 5, $settings['delivery_days'], 0, 0, 'L');

// Price validity
$pdf->SetXY(75, 107);
$pdf->Cell(30, 5, $settings['price_validity_days'], 0, 0, 'L');

// VAT REG or NON VAT checkbox
// We'll add a filled square for the selected option
$pdf->SetFillColor(0, 0, 0);
if (isset($settings['vat_reg']) && $settings['vat_reg']) {
    $pdf->Rect(111, 114, 3, 3, 'F');
} else {
    $pdf->Rect(140, 114, 3, 3, 'F');
}

// Department/Office
$pdf->SetXY(55, 128);
$pdf->Cell(100, 5, $quotation['department'], 0, 0, 'L');

// Purpose
$pdf->SetXY(175, 128);
$pdf->Cell(100, 5, $quotation['purpose'], 0, 0, 'L');

// Items table starts at Y position approximately 142 mm from top
// Each row is about 7.5 mm high
$tableStartY = 150;
$rowHeight = 7.5;

// Calculate grand total
$grandTotal = 0;

// Add items to the table
foreach ($items as $index => $item) {
    $y = $tableStartY + ($index * $rowHeight);
    
    // Only process the first 20 items (that's all that fits on the template)
    if ($index >= 20) {
        break;
    }
    
    // Item No.
    $pdf->SetXY(19, $y);
    $pdf->Cell(10, 5, $item['item_no'], 0, 0, 'C');
    
    // Quantity
    $pdf->SetXY(35, $y);
    $pdf->Cell(10, 5, $item['quantity'], 0, 0, 'C');
    
    // Unit
    $pdf->SetXY(52, $y);
    $pdf->Cell(15, 5, $item['unit'], 0, 0, 'C');
    
    // Description
    $pdf->SetXY(75, $y);
    $pdf->Cell(60, 5, $item['description'], 0, 0, 'L');
    
    // Unit Price
    $pdf->SetXY(150, $y);
    $pdf->Cell(20, 5, number_format($item['final_price'], 2), 0, 0, 'R');
    
    // Total Amount
    $pdf->SetXY(180, $y);
    $pdf->Cell(20, 5, number_format($item['total_amount'], 2), 0, 0, 'R');
    
    $grandTotal += $item['total_amount'];
}

// Grand Total
$pdf->SetXY(180, 300);
$pdf->Cell(20, 5, number_format($grandTotal, 2), 0, 0, 'R');

// Printed Name/Signature (Supplier/Dealer)
$pdf->SetXY(145, 325);
$pdf->Cell(50, 5, $settings['printed_name'], 0, 0, 'C');

// Contact information
$pdf->SetXY(177, 335);
$pdf->Cell(30, 5, $settings['contact_number'], 0, 0, 'L');

$pdf->SetXY(172, 342);
$pdf->Cell(30, 5, $settings['tel_number'] ?? '', 0, 0, 'L');

$pdf->SetXY(172, 349);
$pdf->Cell(30, 5, $settings['email'], 0, 0, 'L');

$pdf->SetXY(172, 356);
$pdf->Cell(30, 5, $settings['fb_page'] ?? '', 0, 0, 'L');

// Output the PDF
$pdf->Output('Quotation_' . $quotation['quotation_no'] . '.pdf', 'I');