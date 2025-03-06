<?php include 'views/partials/head.php' ?>

<h1>System Settings</h1>

<?php if (isset($_GET['success'])): ?>
    <?php if ($_GET['success'] == '1'): ?>
        <div class="alert alert-success">
            Settings updated successfully!
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            Failed to update settings. Please try again.
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5>Company Information</h5>
    </div>
    <div class="card-body">
        <form action="/settings/update" method="post">
            <input type="hidden" name="id" value="<?= $settings['id'] ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_name" class="form-label required-field">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="<?= htmlspecialchars($settings['company_name']) ?>" required>
                    </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="delivery_days" class="form-label required-field">Delivery Days</label>
                        <input type="number" class="form-control" id="delivery_days" name="delivery_days" value="<?= htmlspecialchars($settings['delivery_days']) ?>" min="1" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="price_validity_days" class="form-label required-field">Price Validity Days</label>
                        <input type="number" class="form-control" id="price_validity_days" name="price_validity_days" value="<?= htmlspecialchars($settings['price_validity_days']) ?>" min="1" required>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<?php include 'views/partials/foot.php' ?>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label required-field">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($settings['address']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="printed_name" class="form-label required-field">Printed Name (Supplier/Dealer)</label>
                        <input type="text" class="form-control" id="printed_name" name="printed_name" value="<?= htmlspecialchars($settings['printed_name']) ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_number" class="form-label required-field">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?= htmlspecialchars($settings['contact_number']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tel_number" class="form-label">Telephone Number</label>
                        <input type="text" class="form-control" id="tel_number" name="tel_number" value="<?= htmlspecialchars($settings['tel_number'] ?? '') ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label required-field">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($settings['email']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fb_page" class="form-label">Facebook Page</label>
                        <input type="text" class="form-control" id="fb_page" name="fb_page" value="<?= htmlspecialchars($settings['fb_page'] ?? '') ?>">
                    </div>
                </div>