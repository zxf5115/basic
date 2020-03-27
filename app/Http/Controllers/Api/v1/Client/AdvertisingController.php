<?php
namespace App\Http\Controllers\Api\v1\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-09
 *
 * 广告中心控制器类
 */
class AdvertisingController extends ApiController
{
  protected $_model = 'App\Models\Service\Client\Advertising';

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];




}
