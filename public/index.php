<?php

// Autoloading dependencies and classes
require __DIR__ . '/../vendor/autoload.php';  // Sudah benar

// Bootstrapping the application
$app = require_once __DIR__ . '/../bootstrap/app.php';  // Sudah benar

// Starting the HTTP kernel and handling the incoming request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();
$kernel->terminate($request, $response);
