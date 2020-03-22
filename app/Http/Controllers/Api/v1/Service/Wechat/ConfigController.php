<?php
namespace App\Http\Controllers\Api\v1\Service\Wechat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-12
 *
 * 微信Config接口控制器类
 */
class ConfigController extends ApiController
{
  protected $_model = 'App\Models\Service\System\Wechat\Config';
}
