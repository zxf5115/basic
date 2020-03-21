<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Models\Service\System\User;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-19
 *
 * 登录接口控制器类
 */
class LoginController extends ApiController
{

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-21
   * ------------------------------------------
   * 登录接口
   * ------------------------------------------
   *
   * 实现登录接口
   *
   * @param Request $request 请求参数
   * @return 成功|失败
   */
  public function index(Request $request)
  {
    $messages = [
      'username.required'  => '请输入用户名称',
      'password.required'  => '请输入用户密码'
    ];

    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required',
    ], $messages);

    if($validator->fails())
    {
      $error = $validator->getMessageBag()->toArray();
      $error = array_values($error);
      $message = $error[0][0] ?? '未知错误';

      return self::error($message);
    }
    else
    {
      $username = $request['username'];
      $password = $request['password'];

      $response = User::getRow(['username' => $username]);

      if(is_null($response))
      {
        return self::error(Code::USER_EXIST);
      }

      // 在特定时间内访问次数过多，就触发访问限制
      // if(User::AccessRestrictions($response))
      // {
      //   return self::error(Code::ACCESS_RESTRICTIONS);
      // }

      // 检验用户输入的密码是否正确
      if(User::checkPassword($response, $password))
      {
        // 设置密码尝试信息
        User::setTryNumber($response);

        return self::error(Code::PASSWORD_ERROR);
      }

      // 获取客户端ip地址
      $response->last_login_ip = $request->getClientIp();

      $userInfo = User::login($response);

      $current_user = [
        'user_id'    => $response->id,
        'company_id' => $response->company_id,
        'username'   => $response->username,
        'realname'   => $response->realname
      ];

      Redis::hmset('current_user', $current_user);

      return self::success();
    }
  }
}
