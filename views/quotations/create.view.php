<?php include 'views/partials/head.php'; ?>

<h1>Create New Quotation</h1>

<form action="/quotations/store" method="post" id="quotationForm">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Quotation Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="quotation_no" class="form-label required-field">Quotation No.</label>
                        <input type="text" class="form-control" id="quotation_no" name="quotation_no" value="<?= htmlspecialchars($quotationNo) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label required-field">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label required-field">Department/Office</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="mb-3">
                        <label for="purpose" class="form-label required-field">Purpose</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Company Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" value="<?= htmlspecialchars($settings['company_name']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" value="<?= htmlspecialchars($settings['address']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="delivery_days" class="form-label">Delivery Days</label>
                        <input type="text" class="form-control" id="delivery_days" value="<?= htmlspecialchars($settings['delivery_days']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="price_validity_days" class="form-label">Price Validity Days</label>
                        <input type="text" class="form-control" id="price_validity_days" value="<?= htmlspecialchars($settings['price_validity_days']) ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Items</h5>
            <button type="button" class="btn btn-primary btn-sm" id="addItemBtn">Add Item</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="10%">Qty</th>
                            <th width="10%">Unit</th>
                            <th width="30%">Description</th>
                            <th width="15%">Supplier Price</th>
                            <th width="10%">Markup %</th>
                            <th width="15%">Final Price</th>
                            <th width="15%">Total</th>
                            <th width="5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="item-row">
                            <td class="item-number">1</td>
                            <td>
                                <input type="number" name="items[0][quantity]" class="form-control quantity-input" value="1" min="1" required>
                            </td>
                            <td>
                                <input type="text" name="items[0][unit]" class="form-control unit-input" required>
                            </td>
                            <td>
                                <input type="text" name="items[0][description]" class="form-control description-input" required>
                            </td>
                            <td>
                                <input type="number" name="items[0][supplier_price]" class="form-control supplier-price-input" step="0.01" min="0" required>
                            </td>
                            <td>
                                <input type="number" name="items[0][markup_percentage]" class="form-control markup-input" step="0.1" min="0" value="30" required>
                            </td>
                            <td>
                                <input type="number" name="items[0][final_price]" class="form-control final-price-input" step="0.01" min="0" readonly>
                            </td>
                            <td>
                                <input type="number" name="items[0][total_amount]" class="form-control total-input" step="0.01" min="0" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item-btn">×</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7" class="text-end">Grand Total:</th>
                            <th colspan="2">
                                <span id="grandTotal">0.00</span>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-end">Total Profit:</th>
                            <th colspan="2">
                                <span id="totalProfit" class="profit-positive">0.00</span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="/quotations" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Save Quotation</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Add item row
        $('#addItemBtn').click(function() {
            const rowCount = $('#itemsTable tbody tr').length;
            const newRow = $('#itemsTable tbody tr:first').clone();
            
            // Update row number and clear values
            newRow.find('.item-number').text(rowCount + 1);
            newRow.find('input[type="text"], input[type="number"]').each(function() {
                const name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace('[0]', `[${rowCount}]`));
                }
                
                // Keep markup percentage at 30, reset others
                if (!$(this).hasClass('markup-input')) {
                    $(this).val('');
                }
                
                if ($(this).hasClass('quantity-input')) {
                    $(this).val(1);
                }
            });
            
            // Append the new row
            $('#itemsTable tbody').append(newRow);
            
            // Re-bind event handlers
            bindEventHandlers();
        });
        
        // Remove item row
        $(document).on('click', '.remove-item-btn', function() {
            if ($('#itemsTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                updateItemNumbers();
                calculateTotals();
            } else {
                alert('At least one item is required.');
            }
        });
        
        // Initial binding of event handlers
        bindEventHandlers();
        
        function bindEventHandlers() {
            // Calculate price and total on input change
            $('.quantity-input, .supplier-price-input, .markup-input').off('input').on('input', function() {
                const row = $(this).closest('tr');
                calculateRowTotals(row);
                calculateTotals();
            });
        }
        
        function calculateRowTotals(row) {
            const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
            const supplierPrice = parseFloat(row.find('.supplier-price-input').val()) || 0;
            const markupPercentage = parseFloat(row.find('.markup-input').val()) || 0;
            
            // Calculate final price and total amount directly (client-side)
            const finalPrice = supplierPrice * (1 + (markupPercentage / 100));
            const totalAmount = finalPrice * quantity;
            
            // Update the fields immediately
            row.find('.final-price-input').val(finalPrice.toFixed(2));
            row.find('.total-input').val(totalAmount.toFixed(2));
        }
        
        function calculateTotals() {
            let grandTotal = 0;
            let totalProfit = 0;
            
            $('.item-row').each(function() {
                const quantity = parseFloat($(this).find('.quantity-input').val()) || 0;
                const supplierPrice = parseFloat($(this).find('.supplier-price-input').val()) || 0;
                const finalPrice = parseFloat($(this).find('.final-price-input').val()) || 0;
                const totalAmount = parseFloat($(this).find('.total-input').val()) || 0;
                
                grandTotal += totalAmount;
                totalProfit += (finalPrice - supplierPrice) * quantity;
            });
            
            $('#grandTotal').text(grandTotal.toFixed(2));
            $('#totalProfit').text(totalProfit.toFixed(2));
            
            // Update profit color
            if (totalProfit > 0) {
                $('#totalProfit').removeClass('profit-negative').addClass('profit-positive');
            } else {
                $('#totalProfit').removeClass('profit-positive').addClass('profit-negative');
            }
        }
        
        function updateItemNumbers() {
            $('#itemsTable tbody tr').each(function(index) {
                $(this).find('.item-number').text(index + 1);
                
                // Update input names
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    if (name) {
                        const newName = name.replace(/\[\d+\]/, `[${index}]`);
                        $(this).attr('name', newName);
                    }
                });
            });
        }
        
        // Validate form before submission
        $('#quotationForm').on('submit', function(e) {
            const itemCount = $('#itemsTable tbody tr').length;
            let validItems = 0;
            
            // Check if at least one item has a description
            $('#itemsTable tbody tr').each(function() {
                if ($(this).find('.description-input').val().trim() !== '') {
                    validItems++;
                }
            });
            
            if (validItems === 0) {
                e.preventDefault();
                alert('Please add at least one item with a description.');
                return false;
            }
            
            return true;
        });
        
        // Initial calculation for the first row
        calculateRowTotals($('#itemsTable tbody tr:first'));
        calculateTotals();
    });
</script>

<?php include 'views/partials/foot.php'; ?>