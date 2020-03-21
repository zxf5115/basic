<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-21
 *
 * Token接口控制器类
 */
class TokenController extends ApiController
{

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-21
   * ------------------------------------------
   * 获取token
   * ------------------------------------------
   *
   * 通过客户信息获取token
   *
   * @param Request $request 请求参数
   * @return token信息
   */
  public function certification(Request $request)
  {
    $credentials = [
      'username' => $request['username'],
      'password' => $request['password'],
    ];

    if(! $token = auth('api')->attempt($credentials))
    {
      return self::error(Code::API_UNAUTHORIZED);
    }

    $response = $token;

    return self::success($response);
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * Token 销毁
   * ------------------------------------------
   *
   * 在系统退出登录后，调用接口销毁token
   *
   * @return 成功|失败
   */
  public function destroy()
  {
    try
    {
      auth('api')->logout();

      return self::success();
    }
    catch(\Exception $e)
    {
      self::log($e);

      return false;
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-22
   * ------------------------------------------
   * 组装token信息
   * ------------------------------------------
   *
   * 组装token信息
   *
   * @param string $token token
   * @return token数组信息
   */
  protected function respondWithToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth()->factory()->getTTL() * 60
    ]);
  }
}
