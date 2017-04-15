<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
    $api->group(['middleware' => 'cors'], function (Router $api) {

        $api->post('/auth', 'App\Http\Controllers\AuthenticateController@authenticate');
        $api->get('/token', 'App\Http\Controllers\AuthenticateController@getToken');
        $api->post('/logout', 'App\Http\Controllers\AuthenticateController@logout');
        // Authenticated only
        $api->group(['middleware' => 'jwt.auth'], function (Router $api) {

            $api->group(['middleware' => 'role:student'], function (Router $api) {
                $api->post('ues/{id}/subscribe', 'App\Http\Controllers\UeController@subscribe');
                $api->post('ues/{id}/Unsubscribe', 'App\Http\Controllers\UeController@Unsubscribe');
            });
            /*
             * Session routes
             */
            $api->get('ues/{ue_id}/sessions', 'App\Http\Controllers\SessionController@index');
            $api->post('ues/{ue_id}/sessions', 'App\Http\Controllers\SessionController@store');
            $api->match(['put', 'patch'], 'sessions/{session_id}', 'App\Http\Controllers\SessionController@update');
            $api->delete('sessions/{session_id}', 'App\Http\Controllers\SessionController@destroy');

            /**
             * Question Routes
             */
            $api->get('sessions/{session_id}/questions', 'App\Http\Controllers\QuestionController@index');
            $api->post('sessions/{session_id}/questions', 'App\Http\Controllers\QuestionController@store');
            $api->match(['put', 'patch'], 'questions/{question_id}', 'App\Http\Controllers\QuestionController@update');
            $api->match(['put', 'patch'], 'questions/{question_id}', 'App\Http\Controllers\QuestionController@switchState');
            $api->delete('questions/{question_id}', 'App\Http\Controllers\QuestionController@destroy');


            /**
             * Proposition Routes
             */
            $api->get('questions/{question_id}/propositions', 'App\Http\Controllers\PropositionController@index');
            $api->post('questions/{session_id}/propositions', 'App\Http\Controllers\PropositionController@store');
            $api->match(['put', 'patch'], 'propositions/{id}', 'App\Http\Controllers\PropositionController@update');
            $api->delete('propositions/{id}', 'App\Http\Controllers\PropositionController@destroy');

            /**
             * Response Routes
             */
            $api->get('questions/{question_id}/responses', 'App\Http\Controllers\ResponseController@index');
            $api->post('questions/{question_id}/responses', 'App\Http\Controllers\ResponseController@store');
            $api->get('user/role', 'App\Http\Controllers\AuthenticateController@authenticatedUser');
            $api->resource('ues', 'App\Http\Controllers\UeController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);
            /**
             * Stat Routes
             */
            $api->get('stat/question/{id}', 'App\Http\Controllers\StatController@question');

            /*
            // Authenticated student only
            $api->group(['middleware' => 'role:student'], function (Router $api) {
                $api->get('/student', function () {
                    return response()->json('student');
                });
            });

            // Authenticated teacher only
            $api->group(['middleware' => 'role:teacher'], function (Router $api) {
                $api->get('/teacher', function () {
                    return response()->json('teacher');
                });
            });

            $api->get('protected', function () {
                $user = JWTAuth::parseToken()->authenticate();
                return response()->json(['auth' => $user, 'role' => User::find($user->id)->roles()->first()]);
            });
            $api->get('refresh', [
                'middleware' => 'jwt.refresh',
                function () {
                    return response()->json([
                        'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                    ]);
                }
            ]);
            */
        });
    });
});
