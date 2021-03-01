<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();
    $app->add(TwigMiddleware::class);


    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();


    $app->add(BasePathMiddleware::class); // <--- here

    // Add Error Middleware
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
};