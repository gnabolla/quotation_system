<?php
// Create templates directory if it doesn't exist
$templatesDir = dirname(__DIR__) . '/templates';
if (!file_exists($templatesDir)) {
    mkdir($templatesDir, 0755, true);
}

// Upload form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['template_pdf'])) {
    $targetFile = $templatesDir . '/rfq_template.pdf';
    
    // Check file type
    $fileType = strtolower(pathinfo($_FILES['template_pdf']['name'], PATHINFO_EXTENSION));
    if ($fileType != "pdf") {
        $error = "Only PDF files are allowed.";
    } else {
        // Upload the file
        if (move_uploaded_file($_FILES['template_pdf']['tmp_name'], $targetFile)) {
            $success = "The template has been uploaded successfully.";
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }
}

// Include the view
require 'views/template/upload.view.php';