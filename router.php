<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$routes = [
  "/" => "controllers/index.php",
  "/quotations" => "controllers/quotations/index.php",
  "/quotations/create" => "controllers/quotations/create.php",
  "/quotations/store" => "controllers/quotations/store.php",
  "/quotations/show" => "controllers/quotations/show.php",
  "/quotations/pdf" => "controllers/quotations/pdf.php",
  "/settings" => "controllers/settings/index.php",
  "/settings/update" => "controllers/settings/update.php",
  "/api/calculate-price" => "controllers/api/calculate_price.php",
  "/template/upload" => "controllers/template/upload.php",
];

function routesToController(string $uri, array $routes): void
{
  if (array_key_exists($uri, $routes)) {
    require $routes[$uri];
  } else {
    http_response_code(404);
    echo "404 Not Found";
  }
}

routesToController($uri, $routes);