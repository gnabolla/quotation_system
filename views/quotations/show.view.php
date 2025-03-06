<?php include 'views/partials/head.php' ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <?php
    // Check if PDF template exists
    $templateFile = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))) . '/templates/rfq_template.pdf';
    if (!file_exists($templateFile)):
    ?>
        <div class="alert alert-warning mb-4">
            <strong>PDF Template Missing:</strong> The PDF template has not been uploaded yet.
            The "Generate PDF" button will not work correctly until you <a href="/template/upload" class="alert-link">upload the template</a>.
        </div>
    <?php endif; ?>
    <h1>Quotation Details</h1>
    <div>
        <a href="/quotations" class="btn btn-secondary">Back to List</a>
        <a href="/quotations/pdf?id=<?= $quotation['id'] ?>" class="btn btn-primary" target="_blank">Generate PDF</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Quotation Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Quotation No.:</strong> <?= htmlspecialchars($quotation['quotation_no']) ?></p>
                <p><strong>Date:</strong> <?= date('F j, Y', strtotime($quotation['date'])) ?></p>
                <p><strong>Department/Office:</strong> <?= htmlspecialchars($quotation['department']) ?></p>
                <p><strong>Purpose:</strong> <?= htmlspecialchars($quotation['purpose']) ?></p>
                <p><strong>Created At:</strong> <?= date('F j, Y g:i A', strtotime($quotation['created_at'])) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Company Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Company Name:</strong> <?= htmlspecialchars($settings['company_name']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($settings['address']) ?></p>
                <p><strong>Contact Number:</strong> <?= htmlspecialchars($settings['contact_number']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($settings['email']) ?></p>
                <p><strong>Printed Name:</strong> <?= htmlspecialchars($settings['printed_name']) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="profit-info mb-4">
    <h5>Profit Summary</h5>
    <p><strong>Total Profit:</strong> <span class="<?= $totalProfit > 0 ? 'profit-positive' : 'profit-negative' ?>">₱<?= number_format($totalProfit, 2) ?></span></p>
</div>

<div class="card">
    <div class="card-header">
        <h5>Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Supplier Price</th>
                        <th>Markup %</th>
                        <th>Final Price</th>
                        <th>Total</th>
                        <th>Profit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grandTotal = 0;
                    foreach ($items as $item):
                        $itemProfit = ($item['final_price'] - $item['supplier_price']) * $item['quantity'];
                        $grandTotal += $item['total_amount'];
                    ?>
                        <tr>
                            <td><?= $item['item_no'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= htmlspecialchars($item['unit']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td>₱<?= number_format($item['supplier_price'], 2) ?></td>
                            <td><?= number_format($item['markup_percentage'], 2) ?>%</td>
                            <td>₱<?= number_format($item['final_price'], 2) ?></td>
                            <td>₱<?= number_format($item['total_amount'], 2) ?></td>
                            <td class="<?= $itemProfit > 0 ? 'profit-positive' : 'profit-negative' ?>">
                                ₱<?= number_format($itemProfit, 2) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">Grand Total:</th>
                        <th colspan="2">₱<?= number_format($grandTotal, 2) ?></th>
                    </tr>
                    <tr>
                        <th colspan="7" class="text-end">Total Profit:</th>
                        <th colspan="2" class="<?= $totalProfit > 0 ? 'profit-positive' : 'profit-negative' ?>">
                            ₱<?= number_format($totalProfit, 2) ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include 'views/partials/foot.php' ?>