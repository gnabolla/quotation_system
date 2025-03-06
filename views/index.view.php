<?php include 'partials/head.php' ?>

<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold">ISU Quotation System</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">
            Easily create and manage quotations for the Isabela State University procurement process.
        </p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="/quotations/create" class="btn btn-primary btn-lg px-4 gap-3">Create New Quotation</a>
            <a href="/quotations" class="btn btn-outline-secondary btn-lg px-4">View All Quotations</a>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create Quotations</h5>
                <p class="card-text">Quickly generate quotations with automatic calculation of markup and profit.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Manage Items</h5>
                <p class="card-text">Add multiple items with supplier prices and markup percentages.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Generate PDFs</h5>
                <p class="card-text">Export quotations as PDF documents ready to submit to ISU.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/foot.php' ?>