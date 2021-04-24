<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->get('districts', ['uses' => 'NeighborhoodController@index', 'as' => 'neighborhood.index']);
$router->get('districts/{id}', ['uses' => 'NeighborhoodController@getNeighbourhood', 'as' => 'neighborhood.get']);
$router->post('report/create', ['uses' => 'ReportController@create', 'as' => 'report.create']);
$router->post('report/createFeedback', ['uses' => 'ReportController@createFeedback', 'as' => 'report.createFeedback']);

