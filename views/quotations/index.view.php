<?php include 'views/partials/head.php' ?>

<h1>All Quotations</h1>
<?php
// Check if PDF template exists
$templateFile = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))) . '/templates/rfq_template.pdf';
if (!file_exists($templateFile)):
?>
    <div class="alert alert-warning mb-3">
        <strong>PDF Template Missing:</strong> The PDF template for generating quotations has not been uploaded yet.
        PDF generation will not work correctly until you <a href="/template/upload" class="alert-link">upload the template</a>.
    </div>
<?php endif; ?>
<div class="mb-3">
    <a href="/quotations/create" class="btn btn-primary">Create New Quotation</a>
</div>

<?php if (empty($quotations)): ?>
    <div class="alert alert-info">
        No quotations found. Click the button above to create your first quotation.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Quotation No.</th>
                    <th>Date</th>
                    <th>Department</th>
                    <th>Purpose</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotations as $quotation): ?>
                    <tr>
                        <td><?= htmlspecialchars($quotation['quotation_no']) ?></td>
                        <td><?= date('M j, Y', strtotime($quotation['date'])) ?></td>
                        <td><?= htmlspecialchars($quotation['department']) ?></td>
                        <td><?= htmlspecialchars($quotation['purpose']) ?></td>
                        <td><?= date('M j, Y g:i A', strtotime($quotation['created_at'])) ?></td>
                        <td>
                            <a href="/quotations/show?id=<?= $quotation['id'] ?>" class="btn btn-sm btn-info">View</a>
                            <a href="/quotations/pdf?id=<?= $quotation['id'] ?>" class="btn btn-sm btn-secondary" target="_blank">PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'views/partials/foot.php' ?>