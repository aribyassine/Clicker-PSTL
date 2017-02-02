<?php

use App\User;
use Dingo\Api\Routing\Router;
use Tymon\JWTAuth\Facades\JWTAuth;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {

    $api->post('/authenticate', 'App\Http\Controllers\AuthenticateController@authenticate');
    $api->get('/token', 'App\Http\Controllers\AuthenticateController@getToken');
    $api->post('/logout', 'App\Http\Controllers\AuthenticateController@logout');

    $api->group(['middleware' => 'jwt.auth'], function (Router $api) {
        $api->get('protected', function () {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['auth' => $user, 'users' => User::with('roles')->get()]);
        });

        $api->get('/authenticated_user', 'App\Http\Controllers\AuthenticateController@authenticatedUser');

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function () {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function () {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
});
