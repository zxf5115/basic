<?php
namespace App\Http\Controllers\Api\v1\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Service\System\Menu;
use App\Models\Service\System\Permission;
use App\Http\Controllers\Api\ApiController;

/**
 * @author zhangxiaofei [<1326336909@qq.com>]
 * @dateTime 2020-01-19
 *
 * 登录接口控制器类
 */
class MenuController extends ApiController
{
  protected $_model = 'App\Models\Service\System\Menu';

  protected $_order = [
    ['key' => 'sort', 'value' => 'asc'],
  ];

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
    $company_id = self::getCompanyId();

    $condition = [
      'status' => $this->_model::STATUS_ACTIVE,
      'company_id' => $company_id
    ];

    $response = $this->_model::getList($condition, 'sort', 'asc');

    return self::success($response);
  }


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
      'title.required'              => '请您输入角色标题',
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
      $model->pid = $request->pid;
      $model->type = $request->type;
      $model->sort = $request->sort;
      $model->icon = $request->icon ?? '';
      $model->url = $request->url;

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
   * @dateTime 2020-02-12
   * ------------------------------------------
   * 获取列表信息
   * ------------------------------------------
   *
   * 获取列表信息
   *
   * @param Request $request [请求参数]
   * @return [type]
   */
  public function select(Request $request)
  {
    $company_id = self::getCompanyId();
    // 对用户请求进行过滤
    $condition = ['status' => 1, 'company_id' => $company_id];

    $response = $this->_model::getList($condition, 'id', 'asc', true);

    $all_menu_id = array_column($response, 'id');

    $result = [
      'menu' => $response,
      'all_menu_id' => $all_menu_id
    ];

    return self::success($result);
  }



  /**
   * @author zhangxiaofei [<1326336909@qq.com>]
   * @dateTime 2020-02-11
   * ------------------------------------------
   * 获取当前用户角色对应的菜单树
   * ------------------------------------------
   *
   * 获取当前用户角色对应的菜单树
   *
   * @param Request $request [请求参数]
   * @return [菜单树]
   */
  public function tree(Request $request)
  {
    try
    {
      $result = [];

      // 获取菜单tree
      $response = Menu::getMenus();

      return self::success($response);
    }
    catch(\Exception $e)
    {
      self::local($e);

      return self::error(Code::$message[Code::ERROR]);
    }
  }

}
