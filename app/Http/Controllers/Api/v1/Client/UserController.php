<?php
namespace App\Http\Controllers\Api\v1\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use zxf5115\Wechat\Wechat;
use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-06
 *
 * User接口控制器类
 */
class UserController extends ApiController
{
  /**
   * 获取用户信息
   *
   * @author newton
   * @date 2018/7/5
   * @run http://wx.dmtnewton.com/api/wx/user/getLoginCode
   **/
  public function getLoginCode(Request $request)
  {
    $messages = [
      'code.required' => Code::WX_CODE_EXIST,
    ];

    $validator = Validator::make($request->all(), [
      'code' => 'required',
    ], $messages);


    if($validator->fails())
    {
      $error = $validator->getMessageBag()->toArray();
      $error = array_values($error);
      $message = $error[0][0] ?? Code::ERROR;

      return self::error($message);
    }
    else
    {
      // 获取微信code,并且过滤前后空格
      $code = trim($request->code);

      $wechat = new Wechat();

      $response = $wechat->getLoginInfo($code);

      return self::success($response);
    }
  }


  /**
   * 获取用户信息
   *
   * @author newton
   * @date 2018/7/5
   * @run http://wx.dmtnewton.com/api/wx/user/getLoginCode
   **/
  public function getUserInfo(Request $request)
  {
    $messages = [
      'code.required' => Code::WX_CODE_EXIST,
    ];

    $validator = Validator::make($request->all(), [
      'code' => 'required',
    ], $messages);


    if($validator->fails())
    {
      $error = $validator->getMessageBag()->toArray();
      $error = array_values($error);
      $message = $error[0][0] ?? Code::ERROR;

      return self::error($message);
    }
    else
    {
      // 获取微信code,并且过滤前后空格
      $code = $request->code;
      $iv = $request->iv;
      $encryptedData = $request->encryptedData;

      $wechat = new Wechat();

      $response = $wechat->getLoginInfo($code);
      $session_key = $response['session_key'];

      $response = $wechat->getUserInfo($encryptedData, $iv, $session_key);

      User::


      return self::success($response);
    }
  }
}
