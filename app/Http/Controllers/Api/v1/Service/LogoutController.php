<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-22
 *
 * 退出接口控制器类
 */
class LogoutController extends ApiController
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * 退出接口
   * ------------------------------------------
   *
   * 实现登录接口
   *
   * @param Request $request 请求参数
   * @return 成功|失败
   */
  public function index(Request $request)
  {
    // 销毁Token
    auth('api')->logout();

    return self::success([], '退出成功');
  }
}
