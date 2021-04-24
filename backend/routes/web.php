<?php

/** @var \Laravel\Lumen\Routing\Router $router */
$router->group(['prefix' => 'auth'], function () use ($router) {
    // Matches "/api/register
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');

});


$router->get('districts', ['uses' => 'DistrictController@index', 'as' => 'neighborhood.index']);
$router->get('districts/{id}', ['uses' => 'DistrictController@getDistrict', 'as' => 'neighborhood.get']);
$router->post('report/create', ['uses' => 'ReportController@create', 'as' => 'report.create']);
$router->post('report/createFeedback', ['uses' => 'ReportController@createFeedback', 'as' => 'report.createFeedback']);

$router->group(["middleware" => "admin"], function() use ($router) {
    $router->delete("report/delete/{id}", ['uses' => 'ReportController@destroy', 'as' => 'report.destroy']);
});
