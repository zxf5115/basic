<?php
namespace App\Http\Controllers\Api\v1\Service\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-03-22
 *
 * 字典接口控制器类
 */
class DictionaryController extends ApiController
{
  protected $_model = 'App\Models\Service\System\Dictionary';

  protected $_where = ['pid' => 0];

  protected $_params = [
    'pid'
  ];

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];

  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-25
   * ------------------------------------------
   * 新增系统消息
   * ------------------------------------------
   *
   * 具体描述一些细节
   *
   * @param Request $request [description]
   * @return [type]
   */
  public function handle(Request $request)
  {
    $messages = [
      'title.required' => '请您输入字典标题',
      'code.required'   => '请您输入字典代码',
    ];

    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'code' => 'required',
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

      $model->company_id  = self::getCompanyId();
      $model->pid         = $request->pid;
      $model->title       = $request->title;
      $model->code        = $request->code;
      $model->value       = $request->value;
      $model->description = $request->description;
      $model->sort        = $request->sort;
      $model->status      = $request->status;

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

