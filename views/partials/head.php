<!DOCTYPE html>
<html>
<head>
    <title>Quotation System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .table-responsive {
            margin-bottom: 1rem;
        }
        .profit-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .profit-positive {
            color: green;
        }
        .profit-negative {
            color: red;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="mb-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">Quotation System</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= getURI('/') ? 'active' : '' ?>" href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getURI('/quotations') ? 'active' : '' ?>" href="/quotations">Quotations</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= getURI('/settings') ? 'active' : '' ?>" href="/settings">Settings</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>