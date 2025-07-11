<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\ReportController;

$controller = new ReportController();
$controller->run();