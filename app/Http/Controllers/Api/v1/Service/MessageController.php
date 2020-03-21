<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

use App\Models\Service\System\UserMessageRelevance;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-22
 *
 * Message接口控制器类
 */
class MessageController extends ApiController
{
  protected $_model = 'App\Models\Service\System\Message';



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-01-26
   * ------------------------------------------
   * 获取菜单tree
   * ------------------------------------------
   *
   * 获取菜单tree
   *
   * @param Request $request [description]
   * @return 菜单tree
   */
  public function list(Request $request)
  {
    try
    {
      $condition = [
        'status' => 1,
        'company_id' => self::getCompanyId()
      ];

      $response = $this->_model::where($condition)->with(['relevance'=>function($query){
        $query->where(['status'=>1])->with(['user'=>function($query){
          $query->where(['status'=>1]);
        }]);
      }])->paginate(10);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      self::local($e);

      return self::error(Code::$message[Code::ERROR]);
    }
  }



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
      'type.required'    => '请您输入消息类型',
      'title.required'   => '请您输入标题',
      'content.required' => '请您输入内容',
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

      $model->type        = intval($request->type);
      $model->title       = $request->title;
      $model->content     = strval($request->content);
      $model->accept_type = $request->accept_type;
      $model->author      = $request->author;

      // 获取推送用户
      $users = $this->_model::getUsers($request);

      DB::beginTransaction();

      try
      {
        $response = $model->save();

        $relevance = $model->relevance()->createMany($users);

        DB::commit();

        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
      }
      catch(\Exception $e)
      {
        DB::rollback();

        return self::error(Code::$message[Code::HANDLE_FAILURE]);
      }
    }
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-２8
   * ------------------------------------------
   * 消息设置为已读
   * ------------------------------------------
   *
   * 消息设置为已读
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function readed(Request $request)
  {
    try
    {
      $id = $request->id ?? '';

      // 设置为已读
      $response = UserMessageRelevance::setReaded($id, ['status' => 2]);

      return self::success($response);
    }
    catch(\Exception $e)
    {
      return self::error(Code::$message[Code::ERROR]);
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
