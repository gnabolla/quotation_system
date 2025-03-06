<?php include 'views/partials/head.php' ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Upload RFQ Template PDF</h3>
            </div>
            <div class="card-body">
                <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?= $success ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
                <?php endif; ?>
                
                <p>
                    Upload the original ISU Request for Quotation PDF template. This will be used as the base 
                    for generating quotation PDFs with your data overlaid on the exact official document.
                </p>
                
                <form action="/template/upload" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="template_pdf" class="form-label">Select RFQ Template (PDF):</label>
                        <input type="file" class="form-control" id="template_pdf" name="template_pdf" accept=".pdf" required>
                    </div>
                    
                    <div class="mb-3">
                        <p>
                            <strong>Important:</strong> The uploaded PDF should be the official ISU Request for Quotation form. 
                            Make sure it's a clean, blank form with no filled-in information.
                        </p>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Upload Template</button>
                    </div>
                </form>
                
                <?php
                $templateFile = dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/templates/rfq_template.pdf';
                if (file_exists($templateFile)):
                ?>
                <div class="mt-4">
                    <div class="alert alert-info">
                        <strong>Current template:</strong> Last modified on <?= date('F j, Y, g:i a', filemtime($templateFile)) ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/partials/foot.php' ?>