<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
  'namespace' => 'App\Http\Controllers\Api\v1',
  'middleware' => 'serializer:array'
], function ($api)
{
  $api->group([
    'middleware' => 'api.throttle', // 启用节流限制
    'limit' => 1000, // 允许次数
    'expires' => 1, // 分钟
  ], function ($api)
  {


    $api->group(['namespace'=>'Client', 'prefix' => 'client'], function ($api) {

      $api->group(['middleware' => 'refresh.token', 'prefix' => 'user'], function ($api) {
        $api->post('get_login_code', 'UserController@getLoginCode');
      });

    });







    $api->group(['namespace'=>'Service', 'prefix' => 'service'], function ($api) {
      $api->group(['middleware' => 'refresh.token', 'prefix' => 'user'], function ($api) {
        $api->post('login', 'LoginController@index');
        $api->post('logout', 'LogoutController@index');
        $api->post('info', 'UserController@info');

        $api->post('search', 'UserController@search');
        $api->get('list', 'UserController@list');
        $api->get('view/{id?}', 'UserController@view');

        $api->post('tree', 'UserController@tree');
        $api->post('handle', 'UserController@handle');
        $api->post('password', 'UserController@password');
        $api->post('delete', 'UserController@delete');

        $api->post('message', 'UserController@message');
        $api->post('remove_message', 'UserController@remove_message');
      });

      $api->group(['prefix' => 'token'], function ($api)
      {
        $api->post('certification', 'TokenController@certification');
        $api->post('destroy', 'TokenController@destroy');
      });

      $api->group(['middleware' => 'refresh.token', 'prefix' => 'file'], function ($api) {
        $api->post('picture', 'FileController@picture');
        $api->post('avatar', 'FileController@avatar');
      });

      $api->group(['middleware' => 'refresh.token', 'prefix' => 'menu'], function ($api) {
        $api->get('list', 'MenuController@list');
        $api->get('select', 'MenuController@select');
        $api->get('tree', 'MenuController@tree');
        $api->get('view/{id?}', 'MenuController@view');
        $api->post('create', 'MenuController@create');
        $api->post('handle', 'MenuController@handle');
        $api->post('delete/{id?}', 'MenuController@delete');
      });





      $api->group(['middleware' => 'refresh.token', 'prefix' => 'config'], function ($api) {
        $api->get('list', 'ConfigController@list');
        $api->get('select', 'ConfigController@select');
        $api->get('view/{id?}', 'RoleController@view');
        $api->post('create', 'RoleController@create');
        $api->post('handle', 'RoleController@handle');
        $api->post('delete/{id?}', 'RoleController@delete');
      });

      $api->group(['middleware' => 'refresh.token', 'prefix' => 'log'], function ($api) {
        $api->get('list', 'LogController@list');
      });

      $api->group(['middleware' => 'refresh.token', 'prefix' => 'crontab'], function ($api) {
        $api->get('list', 'CrontabController@list');
        $api->get('select', 'CrontabController@select');
        $api->get('view/{id?}', 'CrontabController@view');
        $api->post('create', 'CrontabController@create');
        $api->post('handle', 'CrontabController@handle');
        $api->post('delete/{id?}', 'CrontabController@delete');
        $api->post('logs/{id?}', 'CrontabLogController@list');
      });


      $api->group(['middleware' => 'refresh.token', 'prefix' => 'message'], function ($api) {
        $api->get('list', 'MessageController@list');
        $api->get('type', 'MessageController@type');
        $api->get('view/{id?}', 'MessageController@view');
        $api->post('handle', 'MessageController@handle');
        $api->post('readed', 'MessageController@readed');
        $api->post('delete', 'MessageController@delete');
      });

      $api->group(['middleware' => 'refresh.token', 'prefix' => 'role'], function ($api) {
        $api->get('list', 'RoleController@list');
        $api->get('select', 'RoleController@select');
        $api->get('view/{id?}', 'RoleController@view');
        $api->any('permission/{id?}', 'RoleController@permission');
        $api->post('create', 'RoleController@create');
        $api->post('handle', 'RoleController@handle');
        $api->post('delete/{id?}', 'RoleController@delete');
      });


      $api->group(['namespace'=>'Client'], function ($api) {
        $api->group(['middleware' => 'refresh.token', 'prefix' => 'advertising'], function ($api) {
          $api->get('list', 'AdvertisingController@list');
          $api->get('type', 'AdvertisingController@type');
          $api->get('view/{id?}', 'AdvertisingController@view');
          $api->post('handle', 'AdvertisingController@handle');
          $api->post('delete/{id?}', 'AdvertisingController@delete');
        });

      });
    });
  });
});