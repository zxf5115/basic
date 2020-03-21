<?php
namespace App\Http\Controllers\Api\v1\Service\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 新建信息
   * ------------------------------------------
   *
   * 新建信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function handle(Request $request)
  {
    $messages = [
      'title.required'  => '请您输入角色标题',
    ];

    $validator = Validator::make($request->all(), [
      'title' => 'required',
    ], $messages);


    if($validator->fails())
    {
      $error = $validator->getMessageBag()->toArray();
      $error = array_values($error);
      $message = $error[0][0] ?? Code::$message[Code::ERROR];

      return self::error($message);
    }
    else
    {
      $model = $this->_model::firstOrNew(['id' => $request->id]);

      $model->title = $request->title;
      $model->type = intval($request->type);
      $model->start_time = $request->valid_time[0];
      $model->end_time = $request->valid_time[1];
      $model->picture = $request->picture;
      $model->description = $request->description;

      $response = $model->save();

      if($response)
      {
        return self::success('操作成功');
      }
      else
      {
        return self::error('操作失败');
      }
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-23
   * ------------------------------------------
   * 获取消息类型
   * ------------------------------------------
   *
   * 获取消息类型
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function type(Request $request)
  {
    try
    {
      $response = $this->_model::getType();

      return self::success($response);
    }
    catch(\Exception $e)
    {
      self::local($e);

      return self::error(Code::$message[Code::ERROR]);
    }
  }
}
