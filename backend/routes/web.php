<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->get('districts', ['uses' => 'DistrictController@index', 'as' => 'neighborhood.index']);
$router->get('districts/{id}', ['uses' => 'DistrictController@getDistrict', 'as' => 'neighborhood.get']);
$router->post('report/create', ['uses' => 'ReportController@create', 'as' => 'report.create']);
$router->post('report/createFeedback', ['uses' => 'ReportController@createFeedback', 'as' => 'report.createFeedback']);

