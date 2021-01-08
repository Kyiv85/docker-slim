<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Aivo\Band;

require '../../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/api/v1/albums', function (Request $request, Response $response, array $args) {
    try {
        $var = $request->getQueryParams();
        $band = new Band();
        $response = $band->getInformation($var['q']);
    } catch(\Exception $e) {
        $response = [
            'status' => 'Error',
            'mensaje' => $e->getMessage()
        ];
    }
    echo json_encode($response);
    
});
$app->run();