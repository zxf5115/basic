<?php
namespace App\Http\Controllers\Api\v1\Service\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-27
 *
 * 项目工程师接口控制器类
 */
class PersonnelController extends ApiController
{
  protected $_model = 'App\Models\Service\Project\Personnel';

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
      $model->picture = $request->picture;
      $model->company = $request->company;
      $model->position = $request->position;
      $model->address = $request->address;
      $model->mobile = $request->mobile;
      $model->weixin = $request->weixin;
      $model->email = $request->email;
      $model->description = $request->description;
      $model->experience = $request->experience;
      $model->sort = $request->sort;

      $response = $model->save();

      if($response)
      {
        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
      }
      else
      {
        return self::error(Code::$message[Code::HANDLE_FAILURE]);
      }
    }
  }
}
