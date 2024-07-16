<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/OrgChartController.php';

// Create App
$app = AppFactory::create();

// Define routes
$app->post('/upload', \OrgChartController::class . ':upload');
$app->post('/update', \OrgChartController::class . ':update');
$app->get('/view', \OrgChartController::class . ':view');

$app->run();
