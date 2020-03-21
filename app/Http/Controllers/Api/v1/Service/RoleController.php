<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Components\Code;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-02-10
 *
 * Role接口控制器类
 */
class RoleController extends ApiController
{
  protected $_model = 'App\Models\Service\System\Role';

  protected $_order = [
    ['key' => 'id', 'value' => 'asc'],
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
      'title.required' => '请您输入角色名称',
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

      $model->title       = $request->title;
      $model->description = $request->description;

      try
      {
        $response = $model->save();

        return self::success(Code::$message[Code::HANDLE_SUCCESS]);
      }
      catch(\Exception $e)
      {
        return self::error(Code::$message[Code::HANDLE_FAILURE]);
      }
    }
  }


  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-03-08
   * ------------------------------------------
   * 获取当前角色已选择的菜单权限
   * ------------------------------------------
   *
   * 获取当前角色已选择的菜单权限
   *
   * @param Request $request 请求信息
   * @return 当前角色已选择的角色
   */
  public function permission(Request $request)
  {
    try
    {
      if($request->isMethod('get'))
      {
        $role_id = $request->id;

        $role = $this->_model::find($role_id);

        $permission = $role->permission()->get(['menu_id'])->toArray();

        $result['title'] = $role->title;

        $result['permission'] = array_column($permission, 'menu_id');

        return self::success($result);
      }

      $messages = [
        'id.required'      => '角色编号必须存在',
        'menu_id.required' => '请选择权限',
      ];

      $validator = Validator::make($request->all(), [
        'id'      => 'required',
        'menu_id' => 'required',
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

        $model->permission()->delete();

        $data = $this->_model::getMenuId($request->menu_id);

        $response = $model->permission()->createMany($data);

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
    catch(\Exception $e)
    {
      return self::error(Code::$message[Code::HANDLE_FAILURE]);
    }

  }

}
