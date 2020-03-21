<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-21
 *
 * 首页接口控制器类
 */
class IndexController extends ApiController
{
  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-21
   * ------------------------------------------
   * 函数功能简述
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @param Request $request [description]
   * @return [type]
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
      $response = User::getRow(['username' => $username]);

      if(is_null($response))
      {
        return self::error(Code::USER_EXIST);
      }

      // 在特定时间内访问次数过多，就触发访问限制
      if(User::AccessRestrictions($response))
      {
        return self::error(Code::ACCESS_RESTRICTIONS);
      }

      return self::success($response);
    }
  }
}
