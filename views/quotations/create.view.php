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
document.addEventListener('DOMContentLoaded', function() {
    // Function to calculate row totals
    function calculateRow(row) {
        console.log("Calculating row...");
        var quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        var supplierPrice = parseFloat(row.querySelector('.supplier-price-input').value) || 0;
        var markup = parseFloat(row.querySelector('.markup-input').value) || 0;
        
        // Formula: finalPrice = supplierPrice * (1 + markup/100)
        var finalPrice = supplierPrice * (1 + (markup / 100));
        var totalAmount = finalPrice * quantity;
        
        // Set calculated values
        row.querySelector('.final-price-input').value = finalPrice.toFixed(2);
        row.querySelector('.total-input').value = totalAmount.toFixed(2);
        
        console.log("Row calculated - Final Price: " + finalPrice.toFixed(2) + ", Total: " + totalAmount.toFixed(2));
        
        // Recalculate page totals after updating row
        calculateTotals();
    }
    
    // Function to calculate page totals
    function calculateTotals() {
        console.log("Calculating totals...");
        var rows = document.querySelectorAll('#itemsTable tbody tr');
        var grandTotal = 0;
        var totalProfit = 0;
        
        rows.forEach(function(row) {
            var quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            var supplierPrice = parseFloat(row.querySelector('.supplier-price-input').value) || 0;
            var finalPrice = parseFloat(row.querySelector('.final-price-input').value) || 0;
            var total = parseFloat(row.querySelector('.total-input').value) || 0;
            
            grandTotal += total;
            var profit = (finalPrice - supplierPrice) * quantity;
            totalProfit += profit;
            
            console.log("Row - Supplier: " + supplierPrice + ", Final: " + finalPrice + ", Profit: " + profit);
        });
        
        // Update the total displays
        document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
        var profitElement = document.getElementById('totalProfit');
        profitElement.textContent = totalProfit.toFixed(2);
        
        // Update profit color
        if (totalProfit > 0) {
            profitElement.classList.remove('profit-negative');
            profitElement.classList.add('profit-positive');
        } else {
            profitElement.classList.remove('profit-positive');
            profitElement.classList.add('profit-negative');
        }
        
        console.log("Totals calculated - Grand Total: " + grandTotal.toFixed(2) + ", Profit: " + totalProfit.toFixed(2));
    }
    
    // Function to update row numbers
    function updateRowNumbers() {
        var rows = document.querySelectorAll('#itemsTable tbody tr');
        rows.forEach(function(row, index) {
            // Update row number display
            row.querySelector('.item-number').textContent = (index + 1);
            
            // Update all input names with new index
            var inputs = row.querySelectorAll('input');
            inputs.forEach(function(input) {
                var name = input.getAttribute('name');
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    input.setAttribute('name', newName);
                }
            });
        });
    }
    
    // Add a new row
    document.getElementById('addItemBtn').addEventListener('click', function() {
        console.log("Adding new row...");
        // Clone the first row
        var tbody = document.querySelector('#itemsTable tbody');
        var firstRow = tbody.querySelector('tr');
        var newRow = firstRow.cloneNode(true);
        
        // Reset input values except markup
        var inputs = newRow.querySelectorAll('input');
        inputs.forEach(function(input) {
            if (input.classList.contains('quantity-input')) {
                input.value = "1"; // Set quantity to 1
            } else if (input.classList.contains('markup-input')) {
                // Keep markup value (usually 30%)
            } else if (input.classList.contains('final-price-input') || input.classList.contains('total-input')) {
                input.value = ""; // Clear calculated fields
            } else {
                input.value = ""; // Clear other inputs
            }
        });
        
        // Add the new row
        tbody.appendChild(newRow);
        
        // Update row numbers
        updateRowNumbers();
        
        // Attach event listeners to the new row
        attachRowEvents(newRow);
        
        console.log("New row added");
    });
    
    // Remove row event handler
    function handleRemoveRow(e) {
        console.log("Removing row...");
        var tbody = document.querySelector('#itemsTable tbody');
        if (tbody.querySelectorAll('tr').length > 1) {
            var row = e.target.closest('tr');
            row.parentNode.removeChild(row);
            updateRowNumbers();
            calculateTotals();
            console.log("Row removed");
        } else {
            alert('At least one item is required.');
        }
    }
    
    // Input change event handler
    function handleInputChange(e) {
        console.log("Input changed");
        var row = e.target.closest('tr');
        calculateRow(row);
    }
    
    // Attach events to a row
    function attachRowEvents(row) {
        // Remove button
        var removeBtn = row.querySelector('.remove-item-btn');
        removeBtn.addEventListener('click', handleRemoveRow);
        
        // Input fields that trigger calculations
        ['quantity-input', 'supplier-price-input', 'markup-input'].forEach(function(className) {
            var input = row.querySelector('.' + className);
            input.addEventListener('input', handleInputChange);
        });
    }
    
    // Attach events to all initial rows
    document.querySelectorAll('#itemsTable tbody tr').forEach(function(row) {
        attachRowEvents(row);
    });
    
    // Form validation
    document.getElementById('quotationForm').addEventListener('submit', function(e) {
        var rows = document.querySelectorAll('#itemsTable tbody tr');
        var validItemCount = 0;
        
        rows.forEach(function(row) {
            if (row.querySelector('.description-input').value.trim() !== '') {
                validItemCount++;
            }
        });
        
        if (validItemCount === 0) {
            e.preventDefault();
            alert('Please add at least one item with a description.');
        }
    });
    
    // Calculate initial values
    document.querySelectorAll('#itemsTable tbody tr').forEach(function(row) {
        calculateRow(row);
    });
    
    console.log("Quotation form initialization complete");
});
</script>

<?php include 'views/partials/foot.php'; ?>